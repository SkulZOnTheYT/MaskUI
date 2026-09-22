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

- Paper 1.21.4 or newer 1.21.x release
- Java 21
- [Vault](https://github.com/MilkBowl/VaultAPI)
- A Vault-compatible economy plugin, such as EssentialsX Economy

Mask purchases are disabled if Vault cannot find an economy provider. The other
commands and menus remain available.

## Installation

1. Download `MaskUI-2.0.0.jar` from the release artifacts, or build it locally.
2. Install Vault and a Vault-compatible economy provider.
3. Put all plugin JARs in the Paper server's `plugins` directory.
4. Start or restart the server.
5. Edit `plugins/MaskUI/config.yml` to customize prices and messages, then restart
   the server to apply the changes.

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

The compiled plugin is written to `target/MaskUI-2.0.0.jar`. Maven declares the
Paper API and Vault API as provided dependencies, so they are not bundled into
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
