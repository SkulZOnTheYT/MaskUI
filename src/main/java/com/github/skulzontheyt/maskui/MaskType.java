package com.github.skulzontheyt.maskui;

import org.bukkit.ChatColor;
import org.bukkit.Material;
import org.bukkit.potion.PotionEffectType;

import java.util.List;

public enum MaskType {
    SKELETON("skeleton", "&fSkeleton", Material.SKELETON_SKULL, List.of(
            new MaskEffect(PotionEffectType.NIGHT_VISION, 0),
            new MaskEffect(PotionEffectType.SPEED, 0))),
    ZOMBIE("zombie", "&2Zombie", Material.ZOMBIE_HEAD, List.of(
            new MaskEffect(PotionEffectType.NIGHT_VISION, 0),
            new MaskEffect(PotionEffectType.STRENGTH, 0),
            new MaskEffect(PotionEffectType.SLOWNESS, 0))),
    CREEPER("creeper", "&aCreeper", Material.CREEPER_HEAD, List.of(
            new MaskEffect(PotionEffectType.NIGHT_VISION, 0),
            new MaskEffect(PotionEffectType.JUMP_BOOST, 1),
            new MaskEffect(PotionEffectType.RESISTANCE, 1),
            new MaskEffect(PotionEffectType.SPEED, 0))),
    PIGLIN("piglin", "&6Piglin", Material.PIGLIN_HEAD, List.of(
            new MaskEffect(PotionEffectType.NIGHT_VISION, 0),
            new MaskEffect(PotionEffectType.STRENGTH, 1),
            new MaskEffect(PotionEffectType.FIRE_RESISTANCE, 0),
            new MaskEffect(PotionEffectType.RESISTANCE, 0))),
    STEVE("steve", "&3Steve", Material.PLAYER_HEAD, List.of(
            new MaskEffect(PotionEffectType.NIGHT_VISION, 0),
            new MaskEffect(PotionEffectType.SPEED, 1),
            new MaskEffect(PotionEffectType.HASTE, 1),
            new MaskEffect(PotionEffectType.RESISTANCE, 0))),
    WITHER("wither", "&5Wither Skeleton", Material.WITHER_SKELETON_SKULL, List.of(
            new MaskEffect(PotionEffectType.NIGHT_VISION, 0),
            new MaskEffect(PotionEffectType.RESISTANCE, 1),
            new MaskEffect(PotionEffectType.STRENGTH, 1),
            new MaskEffect(PotionEffectType.ABSORPTION, 1),
            new MaskEffect(PotionEffectType.REGENERATION, 0))),
    DRAGON("dragon", "&cDragon", Material.DRAGON_HEAD, List.of(
            new MaskEffect(PotionEffectType.NIGHT_VISION, 0),
            new MaskEffect(PotionEffectType.RESISTANCE, 2),
            new MaskEffect(PotionEffectType.STRENGTH, 2),
            new MaskEffect(PotionEffectType.REGENERATION, 1),
            new MaskEffect(PotionEffectType.ABSORPTION, 1),
            new MaskEffect(PotionEffectType.HASTE, 1)));

    private final String id;
    private final String displayName;
    private final Material material;
    private final List<MaskEffect> effects;

    MaskType(String id, String displayName, Material material, List<MaskEffect> effects) {
        this.id = id;
        this.displayName = displayName;
        this.material = material;
        this.effects = effects;
    }

    public String id() { return id; }
    public String displayName() { return ChatColor.translateAlternateColorCodes('&', displayName); }
    public Material material() { return material; }
    public List<MaskEffect> effects() { return effects; }

    public static MaskType fromId(String id) {
        for (MaskType type : values()) {
            if (type.id.equalsIgnoreCase(id)) return type;
        }
        return null;
    }

    public record MaskEffect(PotionEffectType type, int amplifier) {
        public String label() {
            String words = type.getKey().getKey().replace('_', ' ');
            StringBuilder name = new StringBuilder();
            for (String word : words.split(" ")) {
                name.append(Character.toUpperCase(word.charAt(0))).append(word.substring(1)).append(' ');
            }
            return name.toString().trim() + " " + roman(amplifier + 1);
        }

        private static String roman(int level) {
            return switch (level) { case 1 -> "I"; case 2 -> "II"; case 3 -> "III"; default -> String.valueOf(level); };
        }
    }
}
