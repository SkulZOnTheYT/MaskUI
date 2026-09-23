package com.github.skulzontheyt.maskui;

import org.bukkit.Bukkit;
import org.bukkit.ChatColor;
import org.bukkit.Material;
import org.bukkit.entity.Player;
import org.bukkit.inventory.Inventory;
import org.bukkit.inventory.InventoryHolder;
import org.bukkit.inventory.ItemStack;
import org.bukkit.inventory.meta.ItemMeta;
import java.util.ArrayList;
import java.util.List;

public final class MaskMenu implements InventoryHolder {
    public enum Kind { SHOP, WIKI, CONFIRM, SELL, SELL_CONFIRM }
    private static final int[] MASK_SLOTS = {19, 20, 21, 22, 23, 24, 25};
    private final java.util.Map<Integer, MaskType> visibleMasks = new java.util.HashMap<>();
    private final Kind kind;
    private final Inventory inventory;
    private final MaskType selected;
    private final double quotedPrice;
    private final MaskUIPlugin.SellOffer offer;

    public MaskMenu(MaskUIPlugin plugin, Player player, Kind kind) { this(plugin, player, kind, null, null); }
    public MaskMenu(MaskUIPlugin plugin, Player player, MaskType selected) { this(plugin, player, Kind.CONFIRM, selected, null); }

    public static MaskMenu sellConfirmation(MaskUIPlugin plugin, Player player, MaskUIPlugin.SellOffer offer) {
        return new MaskMenu(plugin, player, Kind.SELL_CONFIRM, offer.mask(), offer);
    }

    private MaskMenu(MaskUIPlugin plugin, Player player, Kind kind, MaskType selected, MaskUIPlugin.SellOffer offer) {
        this.kind = kind;
        this.selected = selected;
        this.offer = offer;
        quotedPrice = selected == null ? 0 : plugin.price(selected);
        String path = "gui." + kind.name().toLowerCase(java.util.Locale.ROOT).replace('_', '-') + "-title";
        String defaultTitle;
        switch (kind) {
            case SHOP: defaultTitle = "&eMask Shop"; break;
            case WIKI: defaultTitle = "&dMask Effects"; break;
            case CONFIRM: defaultTitle = "&aConfirm Mask Purchase"; break;
            case SELL: defaultTitle = "&6Sell Your Masks"; break;
            case SELL_CONFIRM: defaultTitle = "&6Confirm Mask Sale"; break;
            default: throw new IllegalArgumentException("Unknown menu kind");
        }
        String title = plugin.getConfig().getString(path, defaultTitle);
        if (title.trim().isEmpty()) title = defaultTitle;
        inventory = Bukkit.createInventory(this, 54, plugin.color(title));
        ItemStack border = named(Material.GRAY_STAINED_GLASS_PANE, " ", java.util.Collections.emptyList());
        for (int slot = 0; slot < inventory.getSize(); slot++) inventory.setItem(slot, border);
        MaskType equipped = plugin.maskOf(player.getInventory().getHelmet());
        inventory.setItem(4, named(Material.EMERALD, "&aBalance: &6" + plugin.balance(player), java.util.Arrays.asList(
                "&7Click to refresh your balance.", "&7Balance is checked again at checkout.")));
        inventory.setItem(8, named(Material.BOOK, "&bMask collection", java.util.Arrays.asList(
                "&7Equipped: &f" + (equipped == null ? "None" : equipped.displayName()),
                "&7Wear a mask in your helmet slot.", "&7Right-click a held mask to equip it.")));
        if (kind == Kind.SELL_CONFIRM) {
            inventory.setItem(22, maskItem(plugin, player, selected));
            inventory.setItem(30, named(Material.LIME_CONCRETE, "&aConfirm sale", java.util.Arrays.asList("&7Receive: &6" + plugin.formatPrice(offer.price()), "&7Consumes ONE purchased mask.", "&7This purchase can only be refunded once.")));
            inventory.setItem(32, named(Material.RED_CONCRETE, "&cCancel", java.util.Arrays.asList("&7Return to sell menu")));
        } else if (kind == Kind.CONFIRM) {
            inventory.setItem(22, maskItem(plugin, player, selected));
            inventory.setItem(30, named(Material.LIME_CONCRETE, "&aConfirm purchase", java.util.Arrays.asList("&7Pay: &6" + plugin.formatPrice(quotedPrice), "&7One mask will be added to your inventory.")));
            inventory.setItem(32, named(Material.RED_CONCRETE, "&cCancel", java.util.Arrays.asList("&7Return to shop")));
        } else {
            int index = 0;
            for (MaskType mask : MaskType.values()) {
                if (kind == Kind.SELL && plugin.sellOffer(player, mask) == null) continue;
                int slot = MASK_SLOTS[index++];
                visibleMasks.put(slot, mask);
                inventory.setItem(slot, maskItem(plugin, player, mask));
            }
            if (kind == Kind.SELL && visibleMasks.isEmpty()) {
                inventory.setItem(22, named(Material.HOPPER, "&cNo masks available to sell", java.util.Arrays.asList(
                        "&7Put a purchased mask in your inventory.",
                        "&7Remove it from your helmet slot first.",
                        "&7Old untracked and admin masks cannot sell.",
                        "&7Selling also requires an available economy.",
                        "&eThen click Refresh.")));
            }
        }
        if (kind != Kind.SHOP) inventory.setItem(45, named(Material.COMPASS, "&eBack to shop", java.util.Arrays.asList("&7Click to navigate")));
        if (kind != Kind.SELL && kind != Kind.SELL_CONFIRM) inventory.setItem(47, named(Material.GOLD_INGOT, "&6Sell masks", java.util.Arrays.asList("&7Sell masks purchased from this shop.", "&7Keep the mask in your inventory,", "&7not your helmet slot.", "&7Admin masks and untracked masks cannot sell.")));
        inventory.setItem(51, named(Material.SUNFLOWER, "&aRefresh", java.util.Arrays.asList("&7Update balance and availability")));
        inventory.setItem(49, named(Material.BARRIER, "&cClose", java.util.Collections.emptyList()));
    }

    private ItemStack maskItem(MaskUIPlugin plugin, Player player, MaskType mask) {
        List<String> lore = new ArrayList<>();
        if (kind != Kind.SELL && kind != Kind.SELL_CONFIRM) lore.add("&7Buy price: &6" + plugin.formatPrice(plugin.price(mask)));
        lore.add("");
        for (MaskType.MaskEffect effect : mask.effects()) lore.add("&7- &f" + effect.label());
        lore.add("");
        if (plugin.maskOf(player.getInventory().getHelmet()) == mask) lore.add("&aCurrently equipped");
        if (kind == Kind.SELL || kind == Kind.SELL_CONFIRM) {
            MaskUIPlugin.SellOffer candidate = kind == Kind.SELL_CONFIRM ? offer : plugin.sellOffer(player, mask);
            if (candidate == null) lore.add("&cNo eligible purchase in inventory");
            else {
                lore.add("&7Sell one for: &6" + plugin.formatPrice(candidate.price()));
                if (kind == Kind.SELL) lore.add("&eClick to preview sale");
            }
        } else {
            lore.add(plugin.canBuy(player, mask) ? "&aAvailable to purchase" : "&cInsufficient balance / shop unavailable");
            if (kind != Kind.CONFIRM) lore.add("&eClick to preview purchase");
        }
        return named(mask.material(), mask.displayName() + " &eMask", lore);
    }

    private ItemStack named(Material material, String name, List<String> lore) {
        ItemStack item = new ItemStack(material);
        ItemMeta meta = item.getItemMeta();
        meta.setDisplayName(color(name));
        meta.setLore(lore.stream().map(this::color).collect(java.util.stream.Collectors.toList()));
        item.setItemMeta(meta);
        return item;
    }

    private String color(String text) { return ChatColor.translateAlternateColorCodes('&', text); }
    public MaskType maskAt(int slot) {
        return visibleMasks.get(slot);
    }
    public Kind kind() { return kind; }
    public MaskType selected() { return selected; }
    public MaskUIPlugin.SellOffer offer() { return offer; }
    public double quotedPrice() { return quotedPrice; }
    @Override public Inventory getInventory() { return inventory; }
}
