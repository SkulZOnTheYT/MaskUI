package com.github.skulzontheyt.maskui;

import org.bukkit.ChatColor;
import org.bukkit.Material;
import org.bukkit.potion.PotionEffectType;

import java.util.List;

public enum MaskType {
    SKELETON("skeleton", "&fSkeleton", Material.SKELETON_SKULL, java.util.Arrays.asList(
            new MaskEffect("NIGHT_VISION", 0),
            new MaskEffect("SPEED", 1),
            new MaskEffect("HASTE", 0),
            new MaskEffect("WATER_BREATHING", 0))),
    ZOMBIE("zombie", "&2Zombie", Material.ZOMBIE_HEAD, java.util.Arrays.asList(
            new MaskEffect("NIGHT_VISION", 0),
            new MaskEffect("STRENGTH", 1),
            new MaskEffect("RESISTANCE", 0),
            new MaskEffect("REGENERATION", 0))),
    CREEPER("creeper", "&aCreeper", Material.CREEPER_HEAD, java.util.Arrays.asList(
            new MaskEffect("NIGHT_VISION", 0),
            new MaskEffect("SPEED", 2),
            new MaskEffect("JUMP_BOOST", 0),
            new MaskEffect("RESISTANCE", 1))),
    PIGLIN("piglin", "&6Piglin", piglinMaterial(), java.util.Arrays.asList(
            new MaskEffect("NIGHT_VISION", 0),
            new MaskEffect("SPEED", 1),
            new MaskEffect("STRENGTH", 1),
            new MaskEffect("RESISTANCE", 1),
            new MaskEffect("FIRE_RESISTANCE", 0))),
    STEVE("steve", "&3Steve", Material.PLAYER_HEAD, java.util.Arrays.asList(
            new MaskEffect("NIGHT_VISION", 0),
            new MaskEffect("SPEED", 1),
            new MaskEffect("HASTE", 2),
            new MaskEffect("RESISTANCE", 1),
            new MaskEffect("WATER_BREATHING", 0),
            new MaskEffect("FIRE_RESISTANCE", 0))),
    WITHER("wither", "&5Wither Skeleton", Material.WITHER_SKELETON_SKULL, java.util.Arrays.asList(
            new MaskEffect("NIGHT_VISION", 0),
            new MaskEffect("SPEED", 1),
            new MaskEffect("STRENGTH", 2),
            new MaskEffect("RESISTANCE", 1),
            new MaskEffect("REGENERATION", 0),
            new MaskEffect("ABSORPTION", 1),
            new MaskEffect("FIRE_RESISTANCE", 0))),
    DRAGON("dragon", "&cDragon", Material.DRAGON_HEAD, java.util.Arrays.asList(
            new MaskEffect("NIGHT_VISION", 0),
            new MaskEffect("SPEED", 2),
            new MaskEffect("STRENGTH", 2),
            new MaskEffect("HASTE", 2),
            new MaskEffect("RESISTANCE", 2),
            new MaskEffect("REGENERATION", 1),
            new MaskEffect("ABSORPTION", 2),
            new MaskEffect("FIRE_RESISTANCE", 0),
            new MaskEffect("WATER_BREATHING", 0)));

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
    public boolean acceptsMaterial(Material actual) { return actual == material || (this == PIGLIN && actual == Material.PLAYER_HEAD); }
    public List<MaskEffect> effects() { return effects; }

    public static MaskType fromId(String id) {
        for (MaskType type : values()) {
            if (type.id.equalsIgnoreCase(id)) return type;
        }
        return null;
    }

    private static Material piglinMaterial() {
        Material modern = Material.matchMaterial("PIGLIN_HEAD");
        return modern == null ? Material.PLAYER_HEAD : modern;
    }

    public static final class MaskEffect {
        private final PotionEffectType type;
        private final int amplifier;
        private final String name;

        public MaskEffect(String name, int amplifier) {
            this.name = name;
            this.amplifier = amplifier;
            PotionEffectType resolved = PotionEffectType.getByName(name);
            if (resolved == null) {
                String legacy = name;
                if (name.equals("STRENGTH")) legacy = "INCREASE_DAMAGE";
                if (name.equals("HASTE")) legacy = "FAST_DIGGING";
                if (name.equals("RESISTANCE")) legacy = "DAMAGE_RESISTANCE";
                if (name.equals("JUMP_BOOST")) legacy = "JUMP";
                resolved = PotionEffectType.getByName(legacy);
            }
            if (resolved == null) throw new IllegalStateException("Unsupported potion effect: " + name);
            this.type = resolved;
        }
        public PotionEffectType type() { return type; }
        public int amplifier() { return amplifier; }
        public String label() {
            String words = this.name.toLowerCase(java.util.Locale.ROOT).replace('_', ' ');
            StringBuilder name = new StringBuilder();
            for (String word : words.split(" ")) {
                name.append(Character.toUpperCase(word.charAt(0))).append(word.substring(1)).append(' ');
            }
            return name.toString().trim() + " " + roman(amplifier + 1);
        }

        private static String roman(int level) {
            switch (level) { case 1: return "I"; case 2: return "II"; case 3: return "III"; case 4: return "IV"; case 5: return "V"; default: return String.valueOf(level); }
        }
    }
}
