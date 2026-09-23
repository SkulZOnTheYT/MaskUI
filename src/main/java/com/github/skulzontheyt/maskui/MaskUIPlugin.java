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
import java.util.List;
import java.util.Map;
import java.util.HashMap;
import java.util.UUID;
import java.util.Locale;
import org.bukkit.event.inventory.InventoryDragEvent;
import org.bukkit.event.entity.EntityPotionEffectEvent;
import org.bukkit.event.player.PlayerQuitEvent;
import org.bukkit.event.player.PlayerInteractEvent;
import org.bukkit.event.block.Action;
import org.bukkit.event.Event;
import org.bukkit.event.EventPriority;
import org.bukkit.inventory.EquipmentSlot;
import org.bukkit.potion.PotionEffectType;

public final class MaskUIPlugin extends JavaPlugin implements Listener, TabExecutor {
    private final DecimalFormat priceFormat = new DecimalFormat("#,##0.##");
    private NamespacedKey maskKey;
    private Economy economy;
    private NamespacedKey receiptKey;
    private ReceiptStore receipts;
    private boolean tradingReady;
    private final java.util.Set<UUID> tradingPlayers = new java.util.HashSet<>();
    public static final class SellOffer {
        private final MaskType mask;
        private final UUID receipt;
        private final double price;
        public SellOffer(MaskType mask, UUID receipt, double price) { this.mask = mask; this.receipt = receipt; this.price = price; }
        public MaskType mask() { return mask; }
        public UUID receipt() { return receipt; }
        public double price() { return price; }
    }
    private final Map<UUID, Map<PotionEffectType, Integer>> ownedEffects = new HashMap<>();
    private boolean changingEffects;

    @Override
    public void onEnable() {
        saveDefaultConfig();
        maskKey = new NamespacedKey(this, "mask_type");
        receiptKey = new NamespacedKey(this, "purchase_receipt");
        try {
            receipts = new ReceiptStore(getDataFolder().toPath().resolve("receipts.properties"));
            tradingReady = true;
            if (receipts.pendingCount() > 0) getLogger().warning("Unfinished receipts require manual review: " + receipts.pendingCount() + ". They cannot be sold or retried automatically.");
        } catch (java.io.IOException error) {
            getLogger().log(java.util.logging.Level.SEVERE, "Cannot load receipts; buying and selling disabled", error);
        }
        RegisteredServiceProvider<Economy> provider = getServer().getServicesManager().getRegistration(Economy.class);
        if (provider != null) economy = provider.getProvider();
        if (economy == null) getLogger().warning("No Vault economy provider found; purchases will be unavailable.");

        getServer().getPluginManager().registerEvents(this, this);
        if (getCommand("mask") != null) getCommand("mask").setExecutor(this);
        getServer().getScheduler().runTaskTimer(this, this::applyMaskEffects, 20L, 20L);
    }

    @Override
    public boolean onCommand(CommandSender sender, Command command, String label, String[] args) {
        String action = args.length == 0 ? "open" : args[0].toLowerCase(Locale.ROOT);
        if (action.equals("reload") || action.equals("give")) {
            if (!sender.hasPermission("maskui.admin")) { sender.sendMessage(color("&cYou do not have permission.")); return true; }
            if (action.equals("reload")) {
                reloadConfig();
                for (Player online : getServer().getOnlinePlayers()) {
                    if (online.getOpenInventory().getTopInventory().getHolder() instanceof MaskMenu) online.closeInventory();
                }
                sender.sendMessage(color("&aMaskUI configuration reloaded."));
                return true;
            }
            Player target = args.length == 3 ? getServer().getPlayerExact(args[1]) : null;
            MaskType mask = args.length == 3 ? MaskType.fromId(args[2]) : null;
            if (target == null || mask == null) { sender.sendMessage(color("&cUsage: /mask give <online-player> <mask>")); return true; }
            if (target.getInventory().firstEmpty() == -1) { sender.sendMessage(message("inventory-full")); return true; }
            target.getInventory().addItem(createMask(target, mask));
            sender.sendMessage(color("&aMask delivered to " + target.getName()));
            return true;
        }
        if (action.equals("help")) {
            sender.sendMessage(color("&aMaskUI commands:\n&f/mask open|wiki &7- Shop / effect guide\n&f/mask buy <mask> &7- Preview purchase\n&f/mask sell [mask] &7- Sell a purchased mask\n&f/mask status &7- Show equipped mask\n&f/mask github &7- Source code"));
            if (sender.hasPermission("maskui.admin")) sender.sendMessage(color("&f/mask give <player> <mask>\n&f/mask reload"));
            return true;
        }
        if (!(sender instanceof Player)) { sender.sendMessage(message("players-only")); return true; }
        Player player = (Player) sender;
        switch (action) {
            case "open": case "shop": openMenu(player, MaskMenu.Kind.SHOP);
            break;
            case "wiki": openMenu(player, MaskMenu.Kind.WIKI);
            break;
            case "buy": {
                MaskType mask = args.length == 2 ? MaskType.fromId(args[1]) : null;
                if (mask == null) player.sendMessage(color("&cUsage: /mask buy <mask>. Use Tab to see masks."));
                else player.openInventory(new MaskMenu(this, player, mask).getInventory());
            }
            break;
            case "sell": {
                if (args.length == 1) openMenu(player, MaskMenu.Kind.SELL);
                else {
                    MaskType mask = args.length == 2 ? MaskType.fromId(args[1]) : null;
                    if (mask == null) player.sendMessage(color("&cUsage: /mask sell [mask]"));
                    else previewSale(player, mask);
                }
            }
            break;
            case "status": {
                MaskType mask = maskOf(player.getInventory().getHelmet());
                player.sendMessage(color("&aEquipped: ") + (mask == null ? "None" : mask.displayName()));
                if (mask != null) for (MaskType.MaskEffect effect : mask.effects()) player.sendMessage(color("&7- " + effect.label()));
            }
            break;
            case "github": player.sendMessage(color("&aSource code: &bhttps://github.com/SkulZOnTheYT/MaskUI"));
            break;
            default: player.sendMessage(color("&cUnknown subcommand. Use &f/mask help&c."));
            break;
        }
        return true;
    }

    @Override
    public List<String> onTabComplete(CommandSender sender, Command command, String alias, String[] args) {
        List<String> choices = new ArrayList<>();
        if (args.length == 1) {
            choices.addAll(java.util.Arrays.asList("open", "shop", "wiki", "buy", "sell", "status", "help", "github"));
            if (sender.hasPermission("maskui.admin")) choices.addAll(java.util.Arrays.asList("give", "reload"));
        } else if ((args.length == 2 && (args[0].equalsIgnoreCase("buy") || args[0].equalsIgnoreCase("sell"))) ||
                (args.length == 3 && args[0].equalsIgnoreCase("give") && sender.hasPermission("maskui.admin"))) {
            for (MaskType mask : MaskType.values()) choices.add(mask.id());
        } else if (args.length == 2 && args[0].equalsIgnoreCase("give") && sender.hasPermission("maskui.admin")) {
            for (Player player : getServer().getOnlinePlayers()) choices.add(player.getName());
        }
        String input = args[args.length - 1].toLowerCase(Locale.ROOT);
        return choices.stream().filter(value -> value.toLowerCase(Locale.ROOT).startsWith(input)).collect(java.util.stream.Collectors.toList());
    }

    private void openMenu(Player player, MaskMenu.Kind kind) {
        player.openInventory(new MaskMenu(this, player, kind).getInventory());
    }

    @EventHandler
    public void onInventoryClick(InventoryClickEvent event) {
        if (!(event.getView().getTopInventory().getHolder() instanceof MaskMenu)) return;
        MaskMenu menu = (MaskMenu) event.getView().getTopInventory().getHolder();
        event.setCancelled(true);
        if (!(event.getWhoClicked() instanceof Player) || event.getClickedInventory() != event.getView().getTopInventory()) return;
        Player player = (Player) event.getWhoClicked();
        int slot = event.getSlot();
        // Inventory changes are deferred until Bukkit has finished processing this click.
        getServer().getScheduler().runTask(this, () -> {
            if (!player.isOnline() || player.getOpenInventory().getTopInventory() != menu.getInventory()) return;
            if (!player.hasPermission("maskui.command")) { player.closeInventory(); return; }
            if (slot == 4 || slot == 51) {
                if (menu.kind() == MaskMenu.Kind.CONFIRM) player.openInventory(new MaskMenu(this, player, menu.selected()).getInventory());
                else if (menu.kind() == MaskMenu.Kind.SELL_CONFIRM) previewSale(player, menu.selected());
                else openMenu(player, menu.kind());
                return;
            }
            if (slot == 47) { openMenu(player, MaskMenu.Kind.SELL); return; }
            if (slot == 49) { player.closeInventory(); return; }
            if (slot == 45 && menu.kind() != MaskMenu.Kind.SHOP) { openMenu(player, MaskMenu.Kind.SHOP); return; }
            if (menu.kind() == MaskMenu.Kind.SELL_CONFIRM) {
                if (slot == 30) { player.closeInventory(); sell(player, menu.offer()); }
                if (slot == 32) openMenu(player, MaskMenu.Kind.SELL);
                return;
            }
            if (menu.kind() == MaskMenu.Kind.CONFIRM) {
                if (slot == 30) { player.closeInventory(); purchase(player, menu.selected(), menu.quotedPrice()); }
                if (slot == 32) openMenu(player, MaskMenu.Kind.SHOP);
                return;
            }
            MaskType mask = menu.maskAt(slot);
            if (mask != null) {
                if (menu.kind() == MaskMenu.Kind.SELL) previewSale(player, mask);
                else player.openInventory(new MaskMenu(this, player, mask).getInventory());
            }
        });
    }

    // Air interactions may arrive pre-cancelled by Bukkit. Respect the item-use
    // result specifically so protection plugins can still deny this action.
    @EventHandler(priority = EventPriority.HIGHEST)
    public void onMaskRightClick(PlayerInteractEvent event) {
        if (event.getAction() != Action.RIGHT_CLICK_AIR && event.getAction() != Action.RIGHT_CLICK_BLOCK) return;
        if (event.useItemInHand() == Event.Result.DENY) return;
        EquipmentSlot hand = event.getHand();
        if (hand != EquipmentSlot.HAND && hand != EquipmentSlot.OFF_HAND) return;
        Player player = event.getPlayer();
        ItemStack held = hand == EquipmentSlot.HAND ? player.getInventory().getItemInMainHand() : player.getInventory().getItemInOffHand();
        if (maskOf(held) == null) return;
        // Never place a tagged mask as a block: block placement would lose its tag.
        event.setUseInteractedBlock(Event.Result.DENY);
        event.setUseItemInHand(Event.Result.DENY);
        ItemStack currentHelmet = player.getInventory().getHelmet();
        if (currentHelmet != null && !currentHelmet.getType().isAir()) {
            player.sendMessage(color("&cRemove your current helmet first."));
            return;
        }
        ItemStack helmet = held.clone();
        helmet.setAmount(1);
        ItemStack remaining = held.clone();
        remaining.setAmount(held.getAmount() - 1);
        if (hand == EquipmentSlot.HAND) player.getInventory().setItemInMainHand(remaining.getAmount() == 0 ? null : remaining);
        else player.getInventory().setItemInOffHand(remaining.getAmount() == 0 ? null : remaining);
        player.getInventory().setHelmet(helmet);
        player.playSound(player.getLocation(), Sound.ITEM_ARMOR_EQUIP_LEATHER, 1, 1);
        applyMaskEffects();
    }

    @EventHandler
    public void onInventoryDrag(InventoryDragEvent event) {
        if (event.getView().getTopInventory().getHolder() instanceof MaskMenu &&
                event.getRawSlots().stream().anyMatch(slot -> slot < event.getView().getTopInventory().getSize())) event.setCancelled(true);
    }

    private void purchase(Player player, MaskType mask, double quotedPrice) {
        if (!canTrade(player)) return;
        if (!tradingPlayers.add(player.getUniqueId())) return;
        UUID receiptId = UUID.randomUUID();
        try {
            int slot = player.getInventory().firstEmpty();
            if (slot < 0 || slot >= 36) { player.sendMessage(message("inventory-full")); return; }
            double price = price(mask);
            if (!Double.isFinite(price) || price < 0 || Double.compare(price, quotedPrice) != 0) {
                player.sendMessage(color("&cPrice unavailable or changed. Please reopen the shop.")); return;
            }
            if (!economy.has(player, price)) { player.sendMessage(message("no-money")); return; }
            ItemStack item = createMask(player, mask);
            ItemMeta meta = item.getItemMeta();
            meta.getPersistentDataContainer().set(receiptKey, PersistentDataType.STRING, receiptId.toString());
            item.setItemMeta(meta);
            receipts.create(new ReceiptStore.Receipt(receiptId, player.getUniqueId(), mask.id(), price, ReceiptStore.State.PENDING_PURCHASE));
            EconomyResponse response = economy.withdrawPlayer(player, price);
            if (!response.transactionSuccess()) {
                receipts.transition(receiptId, ReceiptStore.State.PENDING_PURCHASE, ReceiptStore.State.VOID);
                player.sendMessage(message("transaction-failed")); return;
            }
            // Persist delivery before activating redemption. A crash stays pending, never retryable.
            player.getInventory().setItem(slot, item);
            player.saveData();
            receipts.transition(receiptId, ReceiptStore.State.PENDING_PURCHASE, ReceiptStore.State.ACTIVE);
            player.sendMessage(message("purchased").replace("{mask}", mask.displayName()).replace("{price}", formatPrice(price)));
            player.playSound(player.getLocation(), Sound.ENTITY_ENDERMAN_TELEPORT, 1, 1);
        } catch (Exception error) { tradeFailure(player, receiptId, error); }
        finally { tradingPlayers.remove(player.getUniqueId()); }
    }

    private boolean canTrade(Player player) {
        if (!tradingReady) { player.sendMessage(color("&cTrading unavailable. Ask an admin to check the receipt journal.")); return false; }
        if (economy == null) { player.sendMessage(message("economy-unavailable")); return false; }
        return true;
    }

    private void tradeFailure(Player player, UUID receipt, Exception error) {
        // Vault has no cross-plugin transaction API: uncertain outcomes must not be replayed.
        tradingReady = false;
        getLogger().log(java.util.logging.Level.SEVERE, "Trading paused. Review receipt " + receipt + " before recovery; do not automatically repeat payment.", error);
        player.sendMessage(color("&cTransaction needs admin review. Reference: " + receipt));
    }

    private void previewSale(Player player, MaskType mask) {
        SellOffer offer = sellOffer(player, mask);
        if (offer == null) { player.sendMessage(color("&cNo eligible purchased mask in your inventory. Remove it from your helmet first. The item must come from a tracked shop purchase.")); return; }
        player.openInventory(MaskMenu.sellConfirmation(this, player, offer).getInventory());
    }

    public SellOffer sellOffer(Player player, MaskType mask) {
        if (!tradingReady || economy == null || !getConfig().getBoolean("selling.enabled", true)) return null;
        for (int slot : saleSlots()) {
            ReceiptStore.Receipt receipt = eligibleReceipt(player, player.getInventory().getItem(slot), mask);
            if (receipt != null) {
                double value = sellPrice(receipt);
                if (value > 0) return new SellOffer(mask, receipt.id(), value);
            }
        }
        return null;
    }

    private ReceiptStore.Receipt eligibleReceipt(Player player, ItemStack item, MaskType mask) {
        if (item == null || item.getAmount() < 1 || !mask.acceptsMaterial(item.getType()) || maskOf(item) != mask) return null;
        String raw = item.getItemMeta().getPersistentDataContainer().get(receiptKey, PersistentDataType.STRING);
        if (raw == null) return null;
        try {
            ReceiptStore.Receipt receipt = receipts.get(UUID.fromString(raw));
            return receipt != null && receipt.state() == ReceiptStore.State.ACTIVE && receipt.mask().equals(mask.id()) ? receipt : null;
        } catch (IllegalArgumentException error) { return null; }
    }

    private double sellPrice(ReceiptStore.Receipt receipt) {
        double fraction = getConfig().getDouble("selling.refund-percent", 50);
        if (!Double.isFinite(fraction) || fraction <= 0 || fraction > 100) return 0;
        return java.math.BigDecimal.valueOf(receipt.paid()).multiply(java.math.BigDecimal.valueOf(fraction))
                .divide(java.math.BigDecimal.valueOf(100), 2, java.math.RoundingMode.DOWN).doubleValue();
    }

    private int[] saleSlots() {
        // Storage/hotbar + off-hand only. Never remove armor or a cursor item.
        return java.util.stream.IntStream.concat(java.util.stream.IntStream.range(0, 36), java.util.stream.IntStream.of(40)).toArray();
    }

    private void sell(Player player, SellOffer offer) {
        if (offer == null || !canTrade(player)) return;
        if (!getConfig().getBoolean("selling.enabled", true)) { player.sendMessage(color("&cSelling is disabled.")); return; }
        if (!tradingPlayers.add(player.getUniqueId())) return;
        try {
            int found = -1;
            for (int slot : saleSlots()) {
                ReceiptStore.Receipt receipt = eligibleReceipt(player, player.getInventory().getItem(slot), offer.mask());
                if (receipt != null && receipt.id().equals(offer.receipt()) && Double.compare(sellPrice(receipt), offer.price()) == 0 && offer.price() > 0) { found = slot; break; }
            }
            if (found == -1) { player.sendMessage(color("&cMask missing, already sold, or refund changed. Reopen the sell menu.")); return; }
            ItemStack original = player.getInventory().getItem(found).clone();
            // Consume the unique receipt durably before touching the item or paying.
            receipts.transition(offer.receipt(), ReceiptStore.State.ACTIVE, ReceiptStore.State.REDEEMING);
            ItemStack remainder = original.clone(); remainder.setAmount(original.getAmount() - 1);
            player.getInventory().setItem(found, remainder.getAmount() == 0 ? null : remainder);
            player.saveData();
            EconomyResponse response = economy.depositPlayer(player, offer.price());
            if (!response.transactionSuccess()) {
                player.getInventory().setItem(found, original);
                player.saveData();
                receipts.transition(offer.receipt(), ReceiptStore.State.REDEEMING, ReceiptStore.State.ACTIVE);
                player.sendMessage(message("transaction-failed")); return;
            }
            receipts.transition(offer.receipt(), ReceiptStore.State.REDEEMING, ReceiptStore.State.SOLD);
            player.sendMessage(color("&aSold " + offer.mask().displayName() + "&a for &6" + formatPrice(offer.price()) + "&a. Balance: &6" + balance(player)));
            player.playSound(player.getLocation(), Sound.ENTITY_EXPERIENCE_ORB_PICKUP, 1, 1);
        } catch (Exception error) { tradeFailure(player, offer.receipt(), error); }
        finally { tradingPlayers.remove(player.getUniqueId()); }
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

    public MaskType maskOf(ItemStack item) {
        if (item == null || !item.hasItemMeta()) return null;
        String id = item.getItemMeta().getPersistentDataContainer().get(maskKey, PersistentDataType.STRING);
        MaskType mask = id == null ? null : MaskType.fromId(id);
        return mask != null && mask.acceptsMaterial(item.getType()) ? mask : null;
    }

    private void applyMaskEffects() {
        changingEffects = true;
        try {
            for (Player player : getServer().getOnlinePlayers()) {
                MaskType mask = maskOf(player.getInventory().getHelmet());
                Map<PotionEffectType, Integer> owned = ownedEffects.computeIfAbsent(player.getUniqueId(), key -> new HashMap<>());
                Map<PotionEffectType, Integer> desired = new HashMap<>();
                if (mask != null && !player.isDead()) for (MaskType.MaskEffect effect : mask.effects()) desired.put(effect.type(), effect.amplifier());
                for (PotionEffectType type : new ArrayList<>(owned.keySet())) {
                    if (!owned.get(type).equals(desired.get(type))) {
                        player.removePotionEffect(type); owned.remove(type);
                    }
                }
                for (Map.Entry<PotionEffectType, Integer> entry : desired.entrySet()) {
                    PotionEffect current = player.getPotionEffect(entry.getKey());
                    // Leave effects from potions, beacons and other plugins alone.
                    if (current != null && !owned.containsKey(entry.getKey())) continue;
                    // 30 seconds, refreshed at 20 seconds: safely above the night-vision fade window.
                    if (current == null || current.getDuration() <= 400) {
                        if (player.addPotionEffect(new PotionEffect(entry.getKey(), 600, entry.getValue(), false, false, true))) owned.put(entry.getKey(), entry.getValue());
                    }
                }
                if (owned.isEmpty()) ownedEffects.remove(player.getUniqueId());
            }
        } finally { changingEffects = false; }
    }

    @EventHandler(priority = org.bukkit.event.EventPriority.MONITOR, ignoreCancelled = true)
    public void onPotionChange(EntityPotionEffectEvent event) {
        if (changingEffects || !(event.getEntity() instanceof Player)) return;
        Player player = (Player) event.getEntity();
        if (event.getAction() == EntityPotionEffectEvent.Action.CHANGED && !event.isOverride()) return;
        Map<PotionEffectType, Integer> owned = ownedEffects.get(player.getUniqueId());
        if (owned != null) owned.remove(event.getModifiedType());
    }

    private void clearEffects(Player player) {
        Map<PotionEffectType, Integer> owned = ownedEffects.remove(player.getUniqueId());
        if (owned == null) return;
        changingEffects = true;
        try { for (PotionEffectType type : owned.keySet()) player.removePotionEffect(type); }
        finally { changingEffects = false; }
    }

    @EventHandler
    public void onQuit(PlayerQuitEvent event) { clearEffects(event.getPlayer()); }

    @Override
    public void onDisable() {
        for (Player player : getServer().getOnlinePlayers()) {
            clearEffects(player);
            if (player.getOpenInventory().getTopInventory().getHolder() instanceof MaskMenu) player.closeInventory();
        }
    }

    public String balance(Player player) { return economy == null ? "Unavailable" : formatPrice(economy.getBalance(player)); }
    public boolean canBuy(Player player, MaskType mask) { return tradingReady && economy != null && Double.isFinite(price(mask)) && price(mask) >= 0 && economy.has(player, price(mask)); }
    public double price(MaskType mask) { return getConfig().getDouble("masks." + mask.id() + ".price"); }
    public String formatPrice(double amount) { return priceFormat.format(amount); }
    public String color(String text) { return ChatColor.translateAlternateColorCodes('&', text == null ? "" : text); }
    private String message(String key) { return color(getConfig().getString("messages.prefix", "") + getConfig().getString("messages." + key, "")); }
}
