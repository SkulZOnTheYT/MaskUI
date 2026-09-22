package com.github.skulzontheyt.maskui;

import org.bukkit.Bukkit;
import org.bukkit.ChatColor;
import org.bukkit.Material;
import org.bukkit.inventory.Inventory;
import org.bukkit.inventory.InventoryHolder;
import org.bukkit.inventory.ItemStack;
import org.bukkit.inventory.meta.ItemMeta;

import java.util.ArrayList;
import java.util.List;

public final class MaskMenu implements InventoryHolder {
    public enum Kind { SHOP, WIKI }

    private final Kind kind;
    private final Inventory inventory;

    public MaskMenu(MaskUIPlugin plugin, Kind kind) {
        this.kind = kind;
        int size = kind == Kind.SHOP ? 27 : 54;
        String path = kind == Kind.SHOP ? "gui.shop-title" : "gui.wiki-title";
        inventory = Bukkit.createInventory(this, size, plugin.color(plugin.getConfig().getString(path, "MaskUI")));
        fill(plugin);
    }

    private void fill(MaskUIPlugin plugin) {
        ItemStack border = named(Material.GRAY_STAINED_GLASS_PANE, " ", List.of());
        for (int slot = 0; slot < inventory.getSize(); slot++) inventory.setItem(slot, border);

        int[] shopSlots = {10, 11, 12, 13, 14, 15, 16};
        int[] wikiSlots = {10, 12, 14, 16, 29, 31, 33};
        int[] slots = kind == Kind.SHOP ? shopSlots : wikiSlots;
        MaskType[] masks = MaskType.values();
        for (int i = 0; i < masks.length; i++) {
            MaskType mask = masks[i];
            List<String> lore = new ArrayList<>();
            if (kind == Kind.SHOP) {
                lore.add("&7Price: &6" + plugin.formatPrice(plugin.price(mask)));
                lore.add("");
                lore.add("&eClick to purchase!");
            } else {
                lore.add("&dEffects:");
                for (MaskType.MaskEffect effect : mask.effects()) lore.add("&e- &f" + effect.label());
            }
            inventory.setItem(slots[i], named(mask.material(), mask.displayName() + " &eMask", lore));
        }
        inventory.setItem(inventory.getSize() - 5, named(Material.BARRIER, "&cClose", List.of()));
    }

    private ItemStack named(Material material, String name, List<String> lore) {
        ItemStack item = new ItemStack(material);
        ItemMeta meta = item.getItemMeta();
        meta.setDisplayName(color(name));
        meta.setLore(lore.stream().map(this::color).toList());
        item.setItemMeta(meta);
        return item;
    }

    private String color(String text) { return ChatColor.translateAlternateColorCodes('&', text); }
    public Kind kind() { return kind; }
    @Override public Inventory getInventory() { return inventory; }
}
