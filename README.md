[![Telegram](https://img.shields.io/badge/Telegram-PresentKim-blue.svg?logo=telegram)](https://t.me/PresentKim)
<img src="./assets/icon/index.svg" height="256" width="256">  

[![License](https://img.shields.io/github/license/PMMPPlugin/Lullaby.svg?label=License)](LICENSE)
[![Poggit](https://poggit.pmmp.io/ci.shield/PMMPPlugin/Lullaby/Lullaby)](https://poggit.pmmp.io/ci/PMMPPlugin/Lullaby)
[![Release](https://img.shields.io/github/release/PMMPPlugin/Lullaby.svg?label=Release)](https://github.com/PMMPPlugin/Lullaby/releases/latest)
[![Download](https://img.shields.io/github/downloads/PMMPPlugin/Lullaby/total.svg?label=Download)](https://github.com/PMMPPlugin/Lullaby/releases/latest)


A plugin healing when a player is lying in bed for PocketMine-MP

## Command
Main command : `/lullaby <heal | delay | lang | reload | save>`

| subcommand | arguments           | description            |
| ---------- | ------------------- | ---------------------- |
| Heal       | \<amount\>          | Set heal amount        |
| Delay      | \<tick\>            | Set heal delay         |
| Lang       | \<language prefix\> | Load default lang file |
| Reload     |                     | Reload all data        |
| Save       |                     | Save all data          |




## Permission
| permission         | default | description       |
| ------------------ | ------- | ----------------- |
| lullaby.cmd        | OP      | main command      |
|                    |         |                   |
| lullaby.cmd.heal   | OP      | heal  subcommand  |
| lullaby.cmd.delay  | OP      | delay subcommand  |
| lullaby.cmd.lang   | OP      | lang subcommand   |
| lullaby.cmd.reload | OP      | reload subcommand |
| lullaby.cmd.save   | OP      | save subcommand   |




## ChangeLog
### v1.0.0 [![Source](https://img.shields.io/badge/source-v1.0.0-blue.png?label=source)](https://github.com/PMMPPlugin/Lullaby/tree/v1.0.0) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/Lullaby/v1.0.0/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/Lullaby/releases/v1.0.0)
- First release
  
  
---
### v1.1.0 [![Source](https://img.shields.io/badge/source-v1.1.0-blue.png?label=source)](https://github.com/PMMPPlugin/Lullaby/tree/v1.1.0) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/Lullaby/v1.1.0/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/Lullaby/releases/v1.1.0)
- \[Fixed\] Can't lying at day
- \[Fixed\] Wakes up automatically because change to morning
  
  
---
### v1.2.0 [![Source](https://img.shields.io/badge/source-v1.2.0-blue.png?label=source)](https://github.com/PMMPPlugin/Lullaby/tree/v1.2.0) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/Lullaby/v1.2.0/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/Lullaby/releases/v1.2.0)
- \[Fixed\] main command config not work
- \[Changed\] permission
- \[Changed\] translation method
- \[Changed\] command structure
  
  
---
### v1.2.1 [![Source](https://img.shields.io/badge/source-v1.2.1-blue.png?label=source)](https://github.com/PMMPPlugin/Lullaby/tree/v1.2.1) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/Lullaby/v1.2.1/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/Lullaby/releases/v1.2.1)
- \[Changed\] Add return type hint
- \[Fixed\] Violation of PSR-0
- \[Changed\] Rename main class to Lullaby
- \[Added\] Add PluginCommand getter and setter
- \[Added\] Add getters and setters to SubCommand
- \[Fixed\] Add api 3.0.0-ALPHA11
- \[Added\] Add website and description
- \[Changed\] Show only subcommands that sender have permission to use
  
  
---
### v1.2.2 [![Source](https://img.shields.io/badge/source-v1.2.2-blue.png?label=source)](https://github.com/PMMPPlugin/Lullaby/tree/v1.2.2) [![Release](https://img.shields.io/github/downloads/PMMPPlugin/Lullaby/v1.2.2/total.png?label=download&colorB=1fadad)](https://github.com/PMMPPlugin/Lullaby/releases/v1.2.2)
- \[Fixed\] Split task class for fix Violation of PSR-0