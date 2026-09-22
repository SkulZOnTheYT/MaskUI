package com.github.skulzontheyt.maskui;

import net.milkbowl.vault.economy.Economy;
import net.milkbowl.vault.economy.EconomyResponse;
import org.bukkit.ChatColor;
import org.bukkit.NamespacedKey;
import org.bukkit.Sound;
import org.bukkit.command.Command;
import org.bukkit.command.CommandSender;
import org.bukkit.command.TabExecutor;
import org.bukkit.entity.Player;
import org.bukkit.event.EventHandler;
import org.bukkit.event.Listener;
import org.bukkit.event.inventory.InventoryClickEvent;
import org.bukkit.inventory.ItemStack;
import org.bukkit.inventory.meta.ItemMeta;
import org.bukkit.persistence.PersistentDataType;
import org.bukkit.plugin.RegisteredServiceProvider;
import org.bukkit.plugin.java.JavaPlugin;
import org.bukkit.potion.PotionEffect;

import java.text.DecimalFormat;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.List;

public final class MaskUIPlugin extends JavaPlugin implements Listener, TabExecutor {
    private final DecimalFormat priceFormat = new DecimalFormat("#,##0.##");
    private NamespacedKey maskKey;
    private Economy economy;

    @Override
    public void onEnable() {
        saveDefaultConfig();
        maskKey = new NamespacedKey(this, "mask_type");
        RegisteredServiceProvider<Economy> provider = getServer().getServicesManager().getRegistration(Economy.class);
        if (provider != null) economy = provider.getProvider();
        if (economy == null) getLogger().warning("No Vault economy provider found; purchases will be unavailable.");

        getServer().getPluginManager().registerEvents(this, this);
        if (getCommand("mask") != null) getCommand("mask").setExecutor(this);
        getServer().getScheduler().runTaskTimer(this, this::applyMaskEffects, 20L, 20L);
    }

    @Override
    public boolean onCommand(CommandSender sender, Command command, String label, String[] args) {
        if (!(sender instanceof Player player)) {
            sender.sendMessage(message("players-only"));
            return true;
        }
        String action = args.length == 0 ? "open" : args[0].toLowerCase();
        switch (action) {
            case "open" -> player.openInventory(new MaskMenu(this, MaskMenu.Kind.SHOP).getInventory());
            case "wiki" -> player.openInventory(new MaskMenu(this, MaskMenu.Kind.WIKI).getInventory());
            case "help" -> player.sendMessage(color("&aMaskUI commands:\n&f/mask open &7- Open the shop\n&f/mask wiki &7- View mask effects\n&f/mask help &7- Show this help\n&f/mask github &7- View the source code"));
            case "github" -> player.sendMessage(color("&aSource code: &bhttps://github.com/SkulZOnTheYT/MaskUI"));
            default -> player.sendMessage(color("&cUnknown subcommand. Use &f/mask help&c."));
        }
        return true;
    }

    @Override
    public List<String> onTabComplete(CommandSender sender, Command command, String alias, String[] args) {
        if (args.length != 1) return List.of();
        String input = args[0].toLowerCase();
        return Arrays.stream(new String[]{"open", "wiki", "help", "github"}).filter(value -> value.startsWith(input)).toList();
    }

    @EventHandler
    public void onInventoryClick(InventoryClickEvent event) {
        if (!(event.getView().getTopInventory().getHolder() instanceof MaskMenu menu)) return;
        event.setCancelled(true);
        if (!(event.getWhoClicked() instanceof Player player) || event.getClickedInventory() != event.getView().getTopInventory()) return;
        if (event.getSlot() == event.getView().getTopInventory().getSize() - 5) {
            player.closeInventory();
            return;
        }
        if (menu.kind() != MaskMenu.Kind.SHOP) return;

        int[] slots = {10, 11, 12, 13, 14, 15, 16};
        for (int i = 0; i < slots.length; i++) {
            if (event.getSlot() == slots[i]) purchase(player, MaskType.values()[i]);
        }
    }

    private void purchase(Player player, MaskType mask) {
        if (economy == null) {
            player.sendMessage(message("economy-unavailable"));
            return;
        }
        if (player.getInventory().firstEmpty() == -1) {
            player.sendMessage(message("inventory-full"));
            player.playSound(player, Sound.BLOCK_ANVIL_LAND, 1, 1);
            return;
        }
        double price = price(mask);
        if (!economy.has(player, price)) {
            player.sendMessage(message("no-money"));
            player.playSound(player, Sound.BLOCK_ANVIL_LAND, 1, 1);
            return;
        }
        EconomyResponse response = economy.withdrawPlayer(player, price);
        if (!response.transactionSuccess()) {
            player.sendMessage(message("transaction-failed"));
            return;
        }
        player.getInventory().addItem(createMask(player, mask));
        String text = message("purchased").replace("{mask}", mask.displayName()).replace("{price}", formatPrice(price));
        player.sendMessage(text);
        player.playSound(player, Sound.ENTITY_ENDERMAN_TELEPORT, 1, 1);
    }

    private ItemStack createMask(Player owner, MaskType mask) {
        ItemStack item = new ItemStack(mask.material());
        ItemMeta meta = item.getItemMeta();
        meta.setDisplayName(mask.displayName() + color(" &fMask"));
        List<String> lore = new ArrayList<>();
        lore.add(color("&bOwner: &c" + owner.getName()));
        lore.add("");
        for (MaskType.MaskEffect effect : mask.effects()) lore.add(color("&d" + effect.label()));
        meta.setLore(lore);
        meta.getPersistentDataContainer().set(maskKey, PersistentDataType.STRING, mask.id());
        item.setItemMeta(meta);
        return item;
    }

    private void applyMaskEffects() {
        for (Player player : getServer().getOnlinePlayers()) {
            ItemStack helmet = player.getInventory().getHelmet();
            if (helmet == null || !helmet.hasItemMeta()) continue;
            String id = helmet.getItemMeta().getPersistentDataContainer().get(maskKey, PersistentDataType.STRING);
            if (id == null) continue;
            MaskType mask = MaskType.fromId(id);
            if (mask == null) continue;
            for (MaskType.MaskEffect effect : mask.effects()) {
                player.addPotionEffect(new PotionEffect(effect.type(), 60, effect.amplifier(), false, false, true));
            }
        }
    }

    public double price(MaskType mask) { return getConfig().getDouble("masks." + mask.id() + ".price"); }
    public String formatPrice(double amount) { return priceFormat.format(amount); }
    public String color(String text) { return ChatColor.translateAlternateColorCodes('&', text == null ? "" : text); }
    private String message(String key) { return color(getConfig().getString("messages.prefix", "") + getConfig().getString("messages." + key, "")); }
}
