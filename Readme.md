# ⚡ NEON SURVIVOR

### A Neon-Powered 2D Arena Survival Shooter

<p align="center">
  <strong>Survive the waves. Upgrade your weapons. Defeat the final boss. Clear the arena.</strong>
</p>

<p align="center">
  <img src="https://github.com/chandravadhannadella/Neon-Survivor/blob/main/assests/screenshots/start.png?raw=true" alt="Neon Survivor Banner" width="100%">
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Status-Playable-00d9a6?style=for-the-badge" alt="Status">
  <img src="https://img.shields.io/badge/Campaign-10%20Waves-ff00cc?style=for-the-badge" alt="Campaign">
  <img src="https://img.shields.io/badge/Language-PHP-777BB4?style=for-the-badge&logo=php&logoColor=white" alt="PHP">
  <img src="https://img.shields.io/badge/Frontend-HTML%20%7C%20CSS%20%7C%20JavaScript-orange?style=for-the-badge" alt="Frontend">
  <img src="https://img.shields.io/badge/Rendering-HTML5%20Canvas-00e5ff?style=for-the-badge" alt="Canvas">
</p>

<p align="center">
  <a href="#-game-overview">Overview</a> •
  <a href="#-features">Features</a> •
  <a href="#-gameplay">Gameplay</a> •
  <a href="#-controls">Controls</a> •
  <a href="#-installation-and-setup">Installation</a> •
  <a href="#-screenshots-and-gameplay">Screenshots</a>
</p>

---

## 🎮 Game Overview

**Neon Survivor** is a browser-based 2D arena survival shooter featuring a futuristic neon environment, progressively challenging enemy waves, weapon upgrades, boss encounters, and a final campaign victory sequence.

Players control a neon-powered survivor trapped inside a hostile arena. Each wave introduces increasing combat pressure, requiring players to move strategically, aim accurately, eliminate enemies, collect rewards, and strengthen their character.

The campaign consists of **10 waves**, culminating in a final boss encounter. Defeating the final boss and clearing the remaining enemies triggers the campaign completion sequence, displaying the player's final statistics and a victory celebration.

The game combines:

- Fast-paced arcade combat
- Neon-inspired visual effects
- Real-time movement and aiming
- Enemy spawning and difficulty progression
- Experience and level progression
- Health and shield management
- Coins, rewards, and collectible power-ups
- Boss health tracking
- Pause, restart, game-over, and victory states
- Particle effects and animated celebrations

> **Objective:** Survive all 10 waves, defeat the final boss, eliminate remaining hostiles, and complete the campaign.

---

## 🌌 Game Preview

<p align="center">
  <img src="https://github.com/chandravadhannadella/Neon-Survivor/blob/main/assests/screenshots/main.png?raw=true" width="90%">
</p>

<p align="center">
  <em>Navigate the arena, eliminate enemies, collect rewards, and survive.</em>
</p>

### Gameplay Demonstration

---

## ✨ Features

### ⚔️ Core Combat System

- Real-time player movement.
- Mouse-directed shooting.
- Projectile-based combat.
- Enemy collision and damage detection.
- Player health and shield protection.
- Temporary invincibility after taking damage.
- Multiple enemy types and elite encounters.
- Enemy health indicators.
- Boss health bars.
- Combat feedback and visual effects.

### 🌊 10-Wave Campaign

The game features a structured 10-wave progression system.

Each wave has its own enemy limits, spawning intervals, difficulty scaling, and elite-enemy probabilities.

| Wave | Campaign Stage | Gameplay Focus |
|---|---|---|
| 01 | Initial Deployment | Learn movement and shooting |
| 02 | Rising Threat | Increased enemy activity |
| 03 | Combat Escalation | Improved enemy pressure |
| 04 | Enemy Swarm | Higher spawning intensity |
| 05 | First Boss Encounter | Boss combat and survival |
| 06 | Reinforcement | Faster enemy pressure |
| 07 | Elite Threat | Stronger enemy encounters |
| 08 | Advanced Combat | High-intensity survival |
| 09 | Final Preparation | Heavy combat and boss encounter |
| 10 | Final Showdown | Final boss and campaign completion |

*The table describes the campaign progression rather than a fixed list of unique missions.*

### 👾 Enemy System

Enemies are generated dynamically within the arena and use different combat characteristics.

The game includes:

- Standard enemies
- Faster hunter-style enemies
- Elite enemies
- Boss enemies
- Mini-enemy reinforcements

Enemy attributes include:

- Health
- Maximum health
- Movement behavior
- Contact damage
- Spawn probability
- Score and XP rewards
- Visual appearance

As the campaign progresses, enemy spawning and difficulty are adjusted according to the active wave configuration.

### 👑 Boss Encounters

Boss fights introduce high-health enemies with dedicated health indicators.

Boss encounters include:

- Dedicated boss health bars
- Boss-specific visual effects
- Increased health and damage
- Combat rewards
- Health and shield recovery rewards
- Final boss defeat feedback

<p align="center">
  <img src="https://github.com/chandravadhannadella/Neon-Survivor/blob/main/assests/screenshots/Screenshot%202026-10-03%20171304.png?raw=true" alt="Neon Survivor Boss Fight" width="90%">
</p>

### 🚀 Player Progression

Players earn experience by eliminating enemies and progress through levels.

Level progression improves the player's combat capabilities.

Progression systems include:

- XP collection
- Level advancement
- Increased maximum health
- Increased maximum shield
- Health and shield restoration on level-up
- Weapon damage progression
- Fire-rate improvements
- Bullet-speed improvements

The player HUD displays current level, XP progress, health, shield, score, coins, eliminations, and damage.

### ❤️ Health and Shield System

The player has two defensive resources:

**Health**
- Represents the player's remaining life.
- Decreases when incoming damage exceeds available shield protection.
- Reaching zero health triggers the game-over state.

**Shield**
- Absorbs incoming damage before health.
- Regenerates after a damage-free delay.
- Cannot exceed the configured maximum shield capacity.
- Can be restored through progression and collectible rewards.

### 💎 Collectible Power-Ups

Power-ups appear during gameplay and can be collected by moving the player over them.

| Power-Up | Effect |
|---|---|
| ❤️ Health | Restores health |
| 🛡️ Shield | Restores shield |
| 💰 Coins | Grants additional coins |

Power-up collection provides additional recovery and rewards during difficult encounters.

### 🏆 Score, Coins, and Eliminations

The game tracks player performance throughout the campaign.

Tracked statistics include:

- Total score
- Coins collected
- Enemy eliminations
- Damage
- Player level
- Campaign wave
- Remaining health and shield

Boss eliminations provide additional rewards.

---

## 🎯 Gameplay

### Main Objective

Survive the campaign by defeating enemies while managing health, shield, movement, and weapon effectiveness.

### Combat Strategy

1. Keep moving to avoid enemy contact.
2. Aim toward approaching enemies using the mouse.
3. Eliminate enemies to gain score, coins, and XP.
4. Collect health and shield power-ups when necessary.
5. Use level progression to improve combat capabilities.
6. Monitor the boss health bar during boss encounters.
7. Defeat the final boss and clear all remaining enemies.
8. Complete the 10-wave campaign.

### Final Wave

Wave 10 is the final campaign encounter.

During the final encounter:

- Enemy pressure increases.
- The final boss becomes active.
- A dedicated boss health bar is displayed.
- Defeating the final boss initiates arena clearance.
- Remaining hostiles must be eliminated.
- The campaign completion state is triggered when the arena is clear.

---

## 🏆 Victory and Game Completion

Completing the campaign triggers a dedicated victory screen.

<p align="center">
  <img src="https://github.com/chandravadhannadella/Neon-Survivor/blob/main/assests/screenshots/victory.png?raw=true" alt="Neon Survivor Victory Screen" width="90%">
</p>

### Victory Screen

The completion screen includes:

- **VICTORY**
- 10 / 10 Waves Cleared
- Final score
- Total eliminations
- Final player level
- Animated confetti
- Firework and particle effects
- Victory sound sequence
- Play Again button

The game also includes a separate game-over screen for unsuccessful runs.

---

## 🕹️ Controls

| Input | Action |
|---|---|
| W | Move Up |
| A | Move Left |
| S | Move Down |
| D | Move Right |
| Mouse | Aim |
| Mouse Click / Hold | Shoot |
| P | Pause / Resume |
| Pause Button | Pause / Resume |
| Play Again | Restart Campaign |

**Tip:** Keep moving while shooting. Staying in one position makes it easier for enemies to reach you.

---

## 🎨 Visual Design

Neon Survivor uses a futuristic arcade-inspired visual style.

### Visual Elements

- Dark space-inspired background
- Neon cyan and magenta accents
- Glowing player and enemy sprites
- Grid-based arena environment
- Animated projectiles
- Particle explosions
- Enemy health bars
- Boss health indicators
- Dynamic HUD
- Victory confetti and fireworks
- Animated overlays and transitions

### User Interface

The game HUD provides real-time visibility into:

| HUD Element | Purpose |
|---|---|
| Level | Current player level |
| Health Bar | Remaining player health |
| Shield Bar | Current shield capacity |
| XP Bar | Experience progression |
| Score | Accumulated points |
| Coins | Currency rewards |
| Kills | Total eliminations |
| Damage | Player damage statistic |
| Wave Indicator | Current campaign progress |
| Enemy Counter | Active enemy count |
| Timer | Run duration |
| Boss HUD | Boss health and encounter status |

---

## 🛠️ Technology Stack

| Technology | Purpose |
|---|---|
| PHP | Main application file and page delivery |
| HTML5 | Game structure and interface |
| CSS3 | Styling, animations, responsive layout |
| JavaScript | Game logic, controls, combat, progression |
| HTML5 Canvas | Real-time game rendering |
| Web Audio API | Procedural gameplay sound effects |

### Architecture

Neon Survivor is implemented as a single-file PHP browser game containing the page structure, styling, and JavaScript gameplay implementation.

The JavaScript game engine manages:

- Player state
- Enemy state
- Projectile movement
- Collision detection
- Health and shield calculations
- Wave progression
- Boss encounters
- Power-up collection
- Score and XP
- Particle rendering
- Game-state transitions
- Victory and game-over handling

The game loop uses `requestAnimationFrame()` for continuous updates and rendering.

---

## 📂 Project Structure

```text
Neon-Survivor/
│
├── README.md
├── Neon_Survivor.php
├── LICENSE
├── .gitignore
│
├── assets/
│   ├── banner/
│   │   └── neon-survivor-banner.png
│   │
│   ├── screenshots/
│   │   ├── gameplay.png
│   │   ├── boss-fight.png
│   │   └── victory-screen.png
│   │
│   └── gifs/
│       └── gameplay-demo.gif
│
└── docs/
    ├── GAMEPLAY.md
    └── CHANGELOG.md
```

*The assets and documentation folders are suggested repository organization. Add them as you create the corresponding files.*

---

## 💻 Installation and Setup

### Prerequisites

- PHP 8.0 or later recommended
- A modern web browser
- Visual Studio Code (recommended)
- PHP installed and available in the system PATH

### Step 1: Clone the Repository

```bash
git clone https://github.com/chandravadhannadella/Neon-Survivor.git
```

### Step 2: Navigate to the Project

```bash
cd Neon-Survivor
```

### Step 3: Start the PHP Development Server

```bash
php -S localhost:8000
```

### Step 4: Open the Game

Visit:

```text
http://localhost:8000/Neon_Survivor.php
```

The game should load in your browser.

### Alternative: Run Using VS Code

1. Open the project folder in Visual Studio Code.
2. Open the integrated terminal.
3. Run the PHP development server command.
4. Open the local URL in Chrome or another modern browser.

---

## 🧪 Testing and Validation

The project has been checked for PHP and JavaScript syntax issues during development.

Areas covered by source-level validation include:

- PHP syntax
- JavaScript syntax
- Player movement and shooting implementation
- Wave configuration
- Boss state handling
- Health and shield limits
- Restart state initialization
- Game-over handling
- Final-wave completion logic
- Victory-screen integration

A complete interactive browser playthrough should still be performed after any major gameplay modification.

---

## 📸 Screenshots and Gameplay

Screenshots and gameplay recordings will be added here to showcase the game's visual design and mechanics.

### Arena Gameplay

![Arena Gameplay](assets/screenshots/gameplay.png)

### Boss Encounter

![Boss Encounter](assets/screenshots/boss-fight.png)

### Campaign Victory

![Campaign Victory](assets/screenshots/victory-screen.png)

### Gameplay GIF

![Gameplay Demo](assets/gifs/gameplay-demo.gif)

---

## 🚀 Roadmap

Potential future improvements:

- [ ] Additional enemy variants
- [ ] More boss attack patterns
- [ ] Additional weapon types
- [ ] Weapon selection system
- [ ] More power-up variations
- [ ] High-score leaderboard
- [ ] Persistent local statistics
- [ ] Sound and volume settings
- [ ] Dedicated mobile touch controls
- [ ] Additional arena environments
- [ ] More visual effects
- [ ] Improved accessibility options
- [ ] Campaign achievements
- [ ] Expanded difficulty modes

---

## 🧠 Development Highlights

This project explores several practical game-development concepts:

- Real-time game loops
- Canvas-based rendering
- Keyboard and mouse event handling
- Collision detection
- Object-based entity management
- Enemy spawning systems
- Difficulty scaling
- Player progression
- Resource management
- State-machine-style game flow
- Animation and particle systems
- UI and gameplay synchronization
- Game completion and restart handling

It was developed as a hands-on project to combine frontend development, JavaScript game mechanics, visual design, and PHP-based browser delivery.

---

## 🎓 Project Information

| Field | Details |
|---|---|
| Project Name | Neon Survivor |
| Category | 2D Arcade Survival Shooter |
| Platform | Web Browser |
| Genre | Action / Survival |
| Campaign | 10 Waves |
| Rendering | HTML5 Canvas |
| Backend / Page Delivery | PHP |
| Frontend | HTML, CSS, JavaScript |
| Development Environment | Visual Studio Code |
| Status | Playable Project |

---

## 🤝 Contributing

Contributions, suggestions, and bug reports are welcome.

If you would like to improve the project:

1. Fork the repository.
2. Create a feature branch.
3. Implement your changes.
4. Test the game.
5. Submit a pull request.

For major changes, please open an issue first to discuss the proposed improvement.

---

## 📜 License

No open-source license has been selected for this repository yet.

A license can be added later to define how others may use, modify, and distribute the project.

---

## 👨‍💻 Author

**Chandravadhan Nadella**

B.Tech — Artificial Intelligence and Machine Learning

GitHub: [@chandravadhannadella](https://github.com/chandravadhannadella)

---

## ⭐ Support the Project

If you find Neon Survivor interesting:

- Give the repository a ⭐ Star.
- Explore the source code.
- Share feedback.
- Suggest new gameplay features.

<p align="center">
  <strong>⚡ SURVIVE THE WAVES. DEFEAT THE BOSS. BECOME THE ULTIMATE NEON SURVIVOR. ⚡</strong>
</p>

<p align="center">
  <sub>Built with PHP, HTML, CSS, JavaScript, and a passion for game development.</sub>
</p>
