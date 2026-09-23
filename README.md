# MaskUI for Paper

MaskUI is a Paper plugin that lets players buy wearable mob-head masks from an
inventory GUI. Each mask grants a different set of potion effects while it is
worn. This branch is a complete Java/Paper port of the original PocketMine-MP
plugin.

## Features

- Inventory-based mask shop and effect wiki
- Seven masks: Skeleton, Zombie, Creeper, Piglin, Steve, Wither Skeleton, and Dragon
- Vault-compatible economy support
- Configurable prices and messages
- Persistent item tags, so ordinary mob heads do not grant mask effects
- Safe purchases that check the player's balance and inventory space first
- Tab completion for all `/mask` subcommands

## Requirements

- Target server range: Paper/Spigot 1.16.x–1.21.x (compiled against Spigot 1.16.1; cross-version runtime testing is still needed)
- Plugin bytecode: Java 8 compatible. Run the Java version required by your Minecraft server; build with JDK 21.
- [Vault](https://github.com/MilkBowl/VaultAPI)
- A Vault-compatible economy plugin, such as EssentialsX Economy

Mask purchases are disabled if Vault cannot find an economy provider. The other
commands and menus remain available.

## Installation

1. Download `MaskUI-1.0.0.jar` from the release artifacts, or build it locally.
2. Install Vault and a Vault-compatible economy provider.
3. Put all plugin JARs in the Paper server's `plugins` directory.
4. Start or restart the server.
5. Edit `plugins/MaskUI/config.yml` to customize prices and messages, then use
   `/mask reload` to apply the changes.

## Commands

| Command | Description | Permission |
| --- | --- | --- |
| `/mask` or `/mask open` | Open the mask shop | `maskui.command` |
| `/mask wiki` | Open the mask effect wiki | `maskui.command` |
| `/mask help` | Show the command list | `maskui.command` |
| `/mask github` | Show the source repository | `maskui.command` |

The `maskui.command` permission is granted to all players by default.

## Configuration

Prices are configured per mask:

```yaml
masks:
  skeleton:
    price: 5000
  dragon:
    price: 35000
```

Messages support legacy `&` color codes and the purchase message supports the
`{mask}` and `{price}` placeholders. The full default configuration is available
in [`src/main/resources/config.yml`](src/main/resources/config.yml).

## Building

Clone the repository and run:

```bash
mvn clean package
```

The compiled plugin is written to `target/MaskUI-1.0.0.jar`. Maven declares the
Spigot API and Vault API as provided dependencies, so they are not bundled into
the plugin JAR.

## Notes for Users of the PocketMine Version

- Bedrock SimpleForms have been replaced by standard Java Edition inventory GUIs.
- BedrockEconomy has been replaced by Vault.
- Existing PocketMine mask items and configuration files cannot be imported
  directly because Paper and PocketMine use different item and plugin formats.
- New Paper masks carry a namespaced persistent tag. Renaming an ordinary mob
  head does not turn it into a functional mask.

## Credits

- Original MaskUI authors: SkulZOnTheYT and Kylan1940
- Based on the original [MaskShop](https://github.com/misael38/MaskShop) concept

## Current behavior

- Night vision lasts 30 seconds and refreshes with 20 seconds remaining, preventing expiry flicker.
- Effects belonging to the plugin are removed within one second of removing or changing masks, and on logout/disable. Existing external potion effects are left alone; mask effects resume after those expire.
- The 54-slot shop and guide show effects, price, balance, equipped mask and purchase availability, with navigation and a separate purchase confirmation.
- Purchases recheck funds, inventory space and the confirmed price. Inventory drag and click actions are protected.

| Command | Purpose | Permission |
| --- | --- | --- |
| `/mask shop` | Alias for the shop | `maskui.command` |
| `/mask buy <mask>` | Open purchase confirmation | `maskui.command` |
| `/mask status` | Show worn mask and its effects | `maskui.command` |
| `/mask sell [mask]` | Open the sell menu or preview one eligible mask | `maskui.command` |
| `/mask give <player> <mask>` | Give one mask to an online player | `maskui.admin` |
| `/mask reload` | Reload prices/messages and close stale menus | `maskui.admin` |

Admin permission defaults to operators. Mask IDs: `skeleton`, `zombie`, `creeper`, `piglin`, `steve`, `wither`, `dragon`.
Existing tagged Paper masks remain compatible. Existing configs use bundled defaults for new menu titles.

## Wearing masks and version policy

Right-click a tagged mask in either hand to equip one item into an empty helmet slot, even with a full inventory. An occupied helmet is never replaced. Remove the mask through the armor slot as usual. Right-clicking a tagged mask does not place it as a block. Ordinary mob heads retain vanilla behavior. Protection plugins may deny item use.

## Survival roles and selling

The current effect table and survival roles are maintained in [wiki.md](wiki.md). Dragon is a strong all-rounder with Speed III; high jump and permanent Slow Falling were removed. Other masks focus on exploration, durability, mobility, Nether travel, mining, or combat.

The main menu shows a dedicated emerald balance tile. Click it or Refresh to update the displayed balance. Use **Sell masks**, `/mask sell`, or `/mask sell <mask>` to preview and confirm selling one purchased item.

Every new shop purchase receives a unique item receipt linked to the purchase buyer UUID (audit only), mask type, actual paid price, and redemption state in `plugins/MaskUI/receipts.properties`. A refund requires BOTH an active matching receipt and its tagged physical item in storage/hotbar/off-hand. Armor/cursor/containers are excluded. Any holder can sell a purchased mask; the seller need not be the original buyer. Admin gifts and older untracked masks stay wearable but cannot be sold. One receipt pays once, including after restart or when duplicated items exist.

```yaml
selling:
  enabled: true
  refund-percent: 50
```

Refunds use the original paid price, rounded down to two decimals, not the current shop price. Valid percentages are greater than 0 and at most 100. Invalid percentages disable refunds. The exact item receipt and refund are rechecked at confirmation. A normal rejected deposit restores the item and receipt.

### Interrupted transaction recovery

The receipt journal is atomically replaced before money-changing steps. Purchase delivery/removal also saves player data. Pending purchases and pending redemptions are never retried automatically: Vault does not offer an atomic transaction shared with inventory and plugin files. An interrupted transaction can therefore need manual compensation, but its receipt cannot be redeemed twice automatically.

Unexpected storage/provider exceptions pause trading until restart; `/mask reload` does not clear this pause. Check the logged receipt UUID against the economy provider's transaction history and the player's saved inventory before any compensation. Stop the server before repairing the journal, keep a backup, and resolve the receipt to the state supported by that evidence. Do not mark a sale ACTIVE unless you have confirmed no refund was paid; do not mark a pending purchase ACTIVE unless payment and item delivery are confirmed. If evidence is insufficient, leave it pending. Back up player data, economy data and the receipt journal together; do not delete the journal or restore it independently.

The journal is loaded strictly; malformed entries disable trading. Confirm menus close on configuration reload. Version remains 1.0.0.

The main menu no longer has an Effect Guide toggle. Effect details remain on shop items and `/mask wiki`.

The sell menu lists only currently eligible masks and their refund, with an empty-state message when none qualify. Each menu has its own fallback title for older configs. Back to shop is navigation, not a purchase action.

## Minecraft compatibility

One JAR targets Minecraft 1.16.x through 1.21.x using `api-version: 1.16`, Java 8 bytecode and the Spigot 1.16.1 API. Potion names resolve using modern names with legacy aliases. No runtime test matrix has been run for this change; compilation establishes the baseline API, not a guarantee across all server builds.

On servers without native `PIGLIN_HEAD`, the Piglin mask uses `PLAYER_HEAD` (default player-head appearance) with its Piglin name, tag and effects. Those fallback masks remain recognized after upgrading the server. Native Piglin Head items are not guaranteed to survive downgrading a world to a version without that material.

Use the Java runtime required by the server itself. The build machine may use JDK 21; Maven emits Java 8 classes. Vault and the economy provider must also support the chosen server version.
