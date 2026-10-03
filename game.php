<?php
session_start();

/*
|--------------------------------------------------------------------------
| NEON SURVIVOR
| One-file PHP browser game
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['highscore'])) {
    $_SESSION['highscore'] = 0;
}

/*
|--------------------------------------------------------------------------
| Save high score
|--------------------------------------------------------------------------
*/
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents("php://input"), true);

    if (is_array($data) && isset($data['score']) && is_numeric($data['score'])) {

        $score = max(0, (int)$data['score']);

        if ($score > $_SESSION['highscore']) {
            $_SESSION['highscore'] = $score;
        }

        header('Content-Type: application/json');

        echo json_encode([
            'success' => true,
            'highscore' => $_SESSION['highscore']
        ]);

        exit;
    }
}

$highscore = $_SESSION['highscore'];
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Neon Survivor</title>

<style>

/* =========================================================
   GLOBAL
========================================================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {

    overflow: hidden;

    background:
        radial-gradient(circle at center,
        #111a3a 0%,
        #060817 45%,
        #02030b 100%);

    font-family:
        "Segoe UI",
        Arial,
        sans-serif;

    color: white;

    user-select: none;
}

/* =========================================================
   GAME CONTAINER
========================================================= */

#gameContainer {

    position: relative;

    width: 100vw;
    height: 100vh;

    overflow: hidden;
}

/* =========================================================
   CANVAS
========================================================= */

canvas {

    position: absolute;

    left: 0;
    top: 0;

    width: 100%;
    height: 100%;

    cursor: crosshair;
}

/* =========================================================
   HUD
========================================================= */

#hud {

    position: absolute;

    top: 18px;
    left: 18px;

    z-index: 10;

    width: 330px;

    padding: 16px;

    border-radius: 16px;

    background:
        rgba(7, 10, 30, 0.78);

    border:
        1px solid rgba(0, 255, 255, 0.25);

    box-shadow:
        0 0 25px rgba(0, 255, 255, 0.08);

    backdrop-filter:
        blur(12px);
}

/* =========================================================
   TOP BAR
========================================================= */

.topRow {

    display: flex;

    justify-content:
        space-between;

    align-items: center;

    margin-bottom: 12px;
}

.logo {

    font-size: 20px;

    font-weight: 800;

    letter-spacing: 3px;

    color: #00f7ff;

    text-shadow:
        0 0 12px #00f7ff;
}

.level {

    color: #ffe600;

    font-weight: 700;
}

/* =========================================================
   BARS
========================================================= */

.barContainer {

    margin-top: 8px;
}

.barLabel {

    display: flex;

    justify-content:
        space-between;

    font-size: 12px;

    margin-bottom: 5px;

    color: #b8c7ff;
}

.bar {

    height: 10px;

    border-radius: 20px;

    overflow: hidden;

    background:
        rgba(255,255,255,0.08);
}

.barFill {

    height: 100%;

    width: 100%;

    transition:
        width .15s linear;
}

#healthBar {

    background:
        linear-gradient(
            90deg,
            #ff1744,
            #ff4d8d
        );

    box-shadow:
        0 0 12px #ff1744;
}

#shieldBar {

    background:
        linear-gradient(
            90deg,
            #00aaff,
            #00ffff
        );

    box-shadow:
        0 0 12px #00ffff;
}

#xpBar {

    background:
        linear-gradient(
            90deg,
            #8a2be2,
            #ff00ff
        );

    box-shadow:
        0 0 12px #ff00ff;
}

/* =========================================================
   STATS
========================================================= */

.stats {

    display: grid;

    grid-template-columns:
        repeat(2, 1fr);

    gap: 8px;

    margin-top: 13px;
}

.stat {

    padding: 8px;

    background:
        rgba(255,255,255,0.04);

    border-radius: 8px;

    font-size: 12px;

    color: #bfc8ff;
}

.stat span {

    display: block;

    color: white;

    font-size: 16px;

    font-weight: 700;

    margin-top: 2px;
}

/* =========================================================
   WAVE INDICATOR
========================================================= */

#waveDisplay {

    position: absolute;

    top: 20px;

    left: 50%;

    transform:
        translateX(-50%);

    z-index: 10;

    padding: 10px 22px;

    border-radius: 30px;

    background:
        rgba(5,10,30,.75);

    border:
        1px solid rgba(255,0,255,.35);

    box-shadow:
        0 0 25px rgba(255,0,255,.15);

    font-weight: 700;

    letter-spacing: 2px;
}

/* =========================================================
   CONTROLS
========================================================= */

#controls {

    position: absolute;

    right: 18px;
    bottom: 18px;

    z-index: 20;

    padding: 14px 18px;

    border-radius: 12px;

    background:
        rgba(4,7,20,.7);

    border:
        1px solid rgba(255,255,255,.1);

    font-size: 12px;

    color: #aab4dd;
}

.key {

    display: inline-block;

    padding: 3px 7px;

    margin: 0 2px;

    border-radius: 5px;

    background:
        #151b38;

    border:
        1px solid #303963;

    color: white;
}

/* =========================================================
   OVERLAY
========================================================= */

.overlay {

    position: absolute;

    inset: 0;

    z-index: 50;

    display: flex;

    justify-content: center;

    align-items: center;

    background:
        rgba(2,3,12,.78);

    backdrop-filter:
        blur(10px);
}

.panel {

    width: min(620px, 90%);

    padding: 45px;

    text-align: center;

    border-radius: 24px;

    background:
        linear-gradient(
            145deg,
            rgba(18,25,65,.95),
            rgba(7,9,25,.97)
        );

    border:
        1px solid rgba(0,255,255,.2);

    box-shadow:
        0 0 80px rgba(0,255,255,.12);
}

.title {

    font-size: clamp(42px, 8vw, 78px);

    font-weight: 900;

    letter-spacing: 8px;

    color: #00f7ff;

    text-shadow:
        0 0 10px #00f7ff,
        0 0 30px #008cff;

    margin-bottom: 10px;
}

.subtitle {

    color: #aab6e8;

    margin-bottom: 30px;

    line-height: 1.6;
}

button {

    border: none;

    cursor: pointer;

    padding: 14px 28px;

    border-radius: 12px;

    font-size: 15px;

    font-weight: 800;

    color: #041016;

    background:
        linear-gradient(
            90deg,
            #00f7ff,
            #00ffa6
        );

    box-shadow:
        0 0 25px rgba(0,255,255,.3);

    transition:
        .2s;
}

button:hover {

    transform:
        translateY(-3px);

    box-shadow:
        0 0 35px rgba(0,255,255,.55);
}

/* =========================================================
   CAMPAIGN VICTORY
========================================================= */

function completeCampaign() {
    if (campaignWon) return;

    /*
       FINAL CAMPAIGN GUARD
       This function is the single terminal path for Wave 10.
       It is intentionally idempotent so a cleanup check, boss death, or
       end-of-wave watchdog can never create duplicate victory states.
    */
    wave = MAX_WAVES;
    wavePhase = "victory";
    waveClearing = true;
    bossesSpawnedThisWave = WAVE_CONFIG[MAX_WAVES].bosses;
    campaignWon = true;
    gameRunning = false;
    paused = false;
    mouse.down = false;
    for (const key in keys) delete keys[key];

    document.getElementById("victoryScore").textContent = Math.floor(score);
    document.getElementById("victoryKills").textContent = kills;
    document.getElementById("victoryLevel").textContent = level;
    document.getElementById("pauseScreen").classList.add("hidden");
    document.getElementById("gameOver").classList.add("hidden");
    document.getElementById("victoryScreen").classList.remove("hidden");
    document.getElementById("bossHud").classList.add("hidden");
    document.getElementById("statusMessage").textContent = "GAME CLEARED — ALL 10 WAVES DEFEATED";
    document.getElementById("statusMessage").style.opacity = "1";
    document.getElementById("waveProgressFill").style.width = "100%";

    createVictoryCelebration();
    sound(880, .18, "sine");
    setTimeout(() => sound(1100, .22, "sine"), 150);
    setTimeout(() => sound(1320, .35, "sine"), 320);

    fetch(window.location.href, {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ score: Math.floor(score) })
    }).then(r => r.json()).then(data => {
        if (data && Number.isFinite(Number(data.highscore))) {
            const hs = document.getElementById("highScore");
            if (hs) hs.textContent = data.highscore;
        }
    }).catch(() => {});
}

function createVictoryCelebration() {
    const colors = ["#00f7ff", "#00ffa6", "#ff00cc", "#ffe600", "#8a2be2"];

    /* Canvas fireworks / burst particles. */
    for (let i = 0; i < 260; i++) {
        const angle = Math.random() * Math.PI * 2;
        const speed = 2 + Math.random() * 9;
        particles.push({
            x: width / 2, y: height / 2,
            vx: Math.cos(angle) * speed, vy: Math.sin(angle) * speed,
            life: 1400 + Math.random() * 1800, maxLife: 3200,
            size: 2 + Math.random() * 5, color: colors[Math.floor(Math.random() * colors.length)]
        });
    }
    for (let i = 0; i < 80; i++) {
        floatingTexts.push({
            x: Math.random() * width, y: height * .35 + Math.random() * height * .3,
            text: i % 3 === 0 ? "★" : "+", color: colors[i % colors.length], life: 1800 + Math.random() * 1600
        });
    }

    /* DOM confetti stays visible above the canvas and behind the victory card. */
    const confetti = document.getElementById("victoryConfetti");
    if (confetti) {
        confetti.innerHTML = "";
        for (let i = 0; i < 95; i++) {
            const piece = document.createElement("i");
            piece.style.left = (Math.random() * 100).toFixed(2) + "%";
            piece.style.background = colors[i % colors.length];
            piece.style.setProperty("--duration", (2.8 + Math.random() * 3.4).toFixed(2) + "s");
            piece.style.setProperty("--delay", (-Math.random() * 4.5).toFixed(2) + "s");
            piece.style.setProperty("--drift", ((Math.random() * 240) - 120).toFixed(0) + "px");
            piece.style.setProperty("--rotation", ((Math.random() * 180) - 90).toFixed(0) + "deg");
            piece.style.width = (5 + Math.random() * 7).toFixed(0) + "px";
            piece.style.height = (9 + Math.random() * 11).toFixed(0) + "px";
            confetti.appendChild(piece);
        }
    }
}


/* =========================================================
   GAME OVER
========================================================= */

.hidden {

    display: none !important;
}

.finalScore {

    font-size: 42px;

    color: #ffe600;

    font-weight: 900;

    margin: 15px;
}

.small {

    color: #7f8bb8;

    font-size: 13px;

    margin-top: 15px;
}

/* =========================================================
   POWER UP MESSAGE
========================================================= */

#powerMessage {

    position: absolute;

    top: 100px;

    left: 50%;

    transform:
        translateX(-50%);

    z-index: 20;

    padding: 10px 22px;

    border-radius: 20px;

    background:
        rgba(0,0,0,.65);

    color: #00ffbb;

    font-weight: 800;

    opacity: 0;

    pointer-events: none;
}

/* =========================================================
   PROFESSIONAL TOP CONTROLS / STATUS
========================================================= */
#gameStatus {
    position:absolute;
    top:18px;
    right:18px;
    z-index:25;
    display:flex;
    align-items:center;
    gap:8px;
}
.statusCard {
    min-width:82px;
    padding:8px 12px;
    border-radius:11px;
    background:rgba(5,9,24,.82);
    border:1px solid rgba(0,255,255,.16);
    box-shadow:0 0 20px rgba(0,255,255,.05);
    text-align:center;
    backdrop-filter:blur(10px);
}
.statusCard small { display:block;color:#7180a8;font-size:9px;letter-spacing:1px; }
.statusCard strong { display:block;color:#fff;font-size:15px;margin-top:2px; }
#pauseButton {
    width:44px;height:38px;padding:0;border-radius:10px;
    background:rgba(5,9,24,.9);color:#eaffff;
    border:1px solid rgba(0,255,255,.22);box-shadow:none;
}
#pauseButton:hover { transform:none;border-color:#00f7ff;box-shadow:0 0 18px rgba(0,255,255,.18); }
#shieldState { color:#6defff; }
#damageFlash {
    position:absolute;inset:0;z-index:30;pointer-events:none;opacity:0;
    background:radial-gradient(circle,transparent 35%,rgba(255,20,65,.42));
    transition:opacity .05s linear;
}
#statusMessage {
    position:absolute;top:92px;left:50%;transform:translateX(-50%);z-index:25;
    padding:9px 18px;border-radius:999px;background:rgba(2,5,15,.88);
    border:1px solid rgba(0,255,255,.18);color:#bffaff;font-size:11px;font-weight:800;
    letter-spacing:1px;opacity:0;pointer-events:none;transition:opacity .18s;
}
#pauseScreen .panel .pauseStats {
    display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin:20px 0;
}
#pauseScreen .pauseStat {padding:12px;border-radius:10px;background:rgba(255,255,255,.04);}
#pauseScreen .pauseStat small {display:block;color:#7784a8;font-size:9px;}
#pauseScreen .pauseStat strong {display:block;margin-top:3px;font-size:18px;color:#fff;}
.secondaryButton { margin-left:8px;background:#171d38;color:#fff;box-shadow:none;border:1px solid #303963; }
.secondaryButton:hover { box-shadow:none;border-color:#00f7ff; }
@media(max-width:700px){
    #gameStatus{top:10px;right:10px}.statusCard{min-width:60px;padding:6px 8px}
    .statusCard small{font-size:7px}.statusCard strong{font-size:12px}#pauseButton{width:38px;height:34px}
}

/* =========================================================
   VICTORY / 10-WAVE CAMPAIGN
========================================================= */
#waveProgress {
    position:absolute;
    top:62px;
    left:50%;
    transform:translateX(-50%);
    z-index:10;
    width:min(360px,45vw);
    height:5px;
    border-radius:99px;
    overflow:hidden;
    background:rgba(255,255,255,.08);
    border:1px solid rgba(255,255,255,.08);
}
#waveProgressFill {
    height:100%;
    width:0%;
    background:linear-gradient(90deg,#00f7ff,#8a2be2,#ff00cc);
    box-shadow:0 0 14px rgba(0,247,255,.65);
    transition:width .2s linear;
}
#bossHud {
    position:absolute; top:76px; left:50%; transform:translateX(-50%); z-index:24;
    width:min(560px,62vw); padding:7px 10px 9px; border-radius:12px;
    background:rgba(12,2,16,.82); border:1px solid rgba(255,23,68,.42);
    box-shadow:0 0 28px rgba(255,0,100,.16); backdrop-filter:blur(8px);
}
#bossHud.hidden { display:none; }
.bossHudTitle { text-align:center; color:#ff7b9a; font-size:10px; font-weight:900; letter-spacing:2px; margin-bottom:5px; }
.bossBar { height:8px; border-radius:99px; overflow:hidden; background:rgba(255,255,255,.08); }
#bossBarFill { height:100%; width:0%; background:linear-gradient(90deg,#ff1744,#ff00cc,#ffe600); box-shadow:0 0 16px rgba(255,0,100,.65); transition:width .12s linear; }
#bossHud.final { border-color:rgba(255,230,0,.65); box-shadow:0 0 35px rgba(255,0,68,.28); animation:finalBossPulse 1s infinite alternate; }
@keyframes finalBossPulse { from { filter:brightness(1); } to { filter:brightness(1.22); } }
@media(max-width:700px){#bossHud{top:72px;width:76vw}}

#victoryScreen {
    overflow:hidden;
    background:
        radial-gradient(circle at 50% 42%, rgba(0,255,166,.16), transparent 32%),
        radial-gradient(circle at 20% 20%, rgba(0,247,255,.10), transparent 28%),
        radial-gradient(circle at 80% 75%, rgba(255,0,204,.10), transparent 28%),
        rgba(2,3,12,.84);
}
#victoryScreen .panel {
    position:relative;
    z-index:2;
    border-color:rgba(0,255,166,.55);
    box-shadow:0 0 90px rgba(0,255,166,.24), 0 0 150px rgba(0,247,255,.10), inset 0 0 50px rgba(0,247,255,.05);
    animation:victoryPanelIn .7s cubic-bezier(.2,.85,.25,1) both, victoryPanelPulse 2.2s ease-in-out 1s infinite alternate;
}
#victoryScreen .title {
    color:#00ffa6;
    text-shadow:0 0 10px #00ffa6,0 0 35px #00aaff,0 0 70px rgba(0,255,166,.55);
    animation:victoryTitleGlow 1.5s ease-in-out infinite alternate;
}
@keyframes victoryPanelIn {
    from { opacity:0; transform:translateY(28px) scale(.92); }
    to { opacity:1; transform:translateY(0) scale(1); }
}
@keyframes victoryPanelPulse {
    from { box-shadow:0 0 70px rgba(0,255,166,.18), 0 0 120px rgba(0,247,255,.06), inset 0 0 45px rgba(0,247,255,.03); }
    to { box-shadow:0 0 105px rgba(0,255,166,.34), 0 0 170px rgba(0,247,255,.12), inset 0 0 65px rgba(0,247,255,.06); }
}
@keyframes victoryTitleGlow {
    from { transform:scale(1); filter:brightness(1); }
    to { transform:scale(1.025); filter:brightness(1.22); }
}
.victoryConfetti {
    position:absolute;
    inset:0;
    overflow:hidden;
    pointer-events:none;
    z-index:1;
}
.victoryConfetti i {
    position:absolute;
    top:-24px;
    width:8px;
    height:15px;
    border-radius:2px;
    opacity:.95;
    animation:confettiFall var(--duration) linear var(--delay) infinite;
    transform:rotate(var(--rotation));
}
@keyframes confettiFall {
    0% { transform:translate3d(0,-30px,0) rotate(0deg); opacity:0; }
    8% { opacity:1; }
    100% { transform:translate3d(var(--drift),110vh,0) rotate(720deg); opacity:.15; }
}
.victoryBadge {
    display:inline-block;
    padding:7px 14px;
    margin-bottom:12px;
    border-radius:999px;
    color:#06130f;
    background:#00ffa6;
    font-size:11px;
    font-weight:900;
    letter-spacing:2px;
    box-shadow:0 0 25px rgba(0,255,166,.4);
}
.victoryStats {
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:8px;
    margin:20px 0 24px;
}
.victoryStat {
    padding:12px;
    border-radius:10px;
    background:rgba(255,255,255,.04);
}
.victoryStat small {display:block;color:#7180a8;font-size:9px;letter-spacing:1px;}
.victoryStat strong {display:block;margin-top:4px;color:#fff;font-size:18px;}
@media(max-width:700px){#waveProgress{top:58px;width:55vw}.victoryStats{grid-template-columns:1fr 1fr}}

/* =========================================================
   MOBILE
========================================================= */

@media(max-width:700px) {

    #hud {

        width: 270px;

        transform:
            scale(.85);

        transform-origin:
            top left;
    }

    #controls {

        display: none;
    }

    #waveDisplay {

        font-size: 12px;
    }
}

</style>

</head>

<body>

<div id="gameContainer">

<canvas id="gameCanvas"></canvas>

<!-- HUD -->

<div id="hud">

    <div class="topRow">

        <div class="logo">
            NEON SURVIVOR
        </div>

        <div class="level">
            LV <span id="level">1</span>
        </div>

    </div>

    <div class="barContainer">

        <div class="barLabel">

            <span>HEALTH</span>

            <span>
                <span id="healthText">100</span>/<span id="healthMaxText">100</span>
            </span>

        </div>

        <div class="bar">
            <div id="healthBar"
                 class="barFill"></div>
        </div>

    </div>

    <div class="barContainer">

        <div class="barLabel">

            <span>SHIELD</span>

            <span id="shieldText">50</span>/<span id="shieldMaxText">50</span>

        </div>

        <div class="bar">
            <div id="shieldBar"
                 class="barFill"></div>
        </div>

    </div>

    <div class="barContainer">

        <div class="barLabel">

            <span>XP</span>

            <span>
                <span id="xpText">0</span>/<span id="xpNeeded">100</span>
            </span>

        </div>

        <div class="bar">
            <div id="xpBar"
                 class="barFill"
                 style="width:0%">
            </div>
        </div>

    </div>

    <div class="stats">

        <div class="stat">
            SCORE
            <span id="score">0</span>
        </div>

        <div class="stat">
            COINS
            <span id="coins">0</span>
        </div>

        <div class="stat">
            KILLS
            <span id="kills">0</span>
        </div>

        <div class="stat">
            DAMAGE
            <span id="damage">20</span>
        </div>

    </div>

</div>

<!-- WAVE -->

<div id="waveDisplay">

    WAVE <span id="wave">1</span>

</div>

<div id="powerMessage"></div>

<!-- PROFESSIONAL STATUS -->
<div id="gameStatus">
    <div class="statusCard"><small>TIME</small><strong id="timeText">00:00</strong></div>
    <div class="statusCard"><small>ENEMIES</small><strong id="enemyCount">0</strong></div>
    <button id="pauseButton" title="Pause / Resume">Ⅱ</button>
</div>
<div id="statusMessage"></div>
<div id="damageFlash"></div>

<div id="waveProgress" title="Current wave progress"><div id="waveProgressFill"></div></div>
<div id="bossHud" class="hidden">
    <div class="bossHudTitle">☠ <span id="bossHudName">BOSS</span> <span id="bossHudCount"></span></div>
    <div class="bossBar"><div id="bossBarFill"></div></div>
</div>

<!-- VICTORY -->
<div id="victoryScreen" class="overlay hidden">
    <div class="victoryConfetti" id="victoryConfetti" aria-hidden="true"></div>
    <div class="panel">
        <div class="victoryBadge">10 / 10 WAVES CLEARED</div>
        <div class="title">VICTORY</div>
        <p class="subtitle">Congratulations! You survived all 10 waves.<br>The final boss and its army have been destroyed. The arena is secured.</p>
        <div class="victoryStats">
            <div class="victoryStat"><small>FINAL SCORE</small><strong id="victoryScore">0</strong></div>
            <div class="victoryStat"><small>ELIMINATIONS</small><strong id="victoryKills">0</strong></div>
            <div class="victoryStat"><small>FINAL LEVEL</small><strong id="victoryLevel">1</strong></div>
        </div>
        <button id="victoryRestartButton">PLAY AGAIN</button>
    </div>
</div>

<!-- CONTROLS -->

<div id="controls">

    <span class="key">W</span>
    <span class="key">A</span>
    <span class="key">S</span>
    <span class="key">D</span>
    Move

    &nbsp;&nbsp;

    <span class="key">Mouse</span>
    Shoot

    &nbsp;&nbsp;

    <span class="key">P</span>
    Pause

</div>

<!-- START SCREEN -->

<div id="startScreen"
     class="overlay">

    <div class="panel">

        <div class="title">
            NEON
        </div>

        <h2>
            SURVIVOR
        </h2>

        <p class="subtitle">

            Enter the cyber arena.<br>

            Survive 10 escalating waves,
            upgrade your weapons,
            collect power-ups and defeat the final boss army.

        </p>

        <button id="startButton">
            ENTER THE ARENA
        </button>

        <p class="small">

            WASD / Arrow Keys to move •
            Mouse to aim and shoot

        </p>

    </div>

</div>

<!-- GAME OVER -->

<div id="gameOver"
     class="overlay hidden">

    <div class="panel">

        <div class="title">
            DEFEATED
        </div>

        <p>
            Your final score
        </p>

        <div class="finalScore"
             id="finalScore">
            0
        </div>

        <p>
            High Score:
            <strong id="highScore">
                <?= $highscore ?>
            </strong>
        </p>

        <br>

        <button id="restartButton">
            PLAY AGAIN
        </button>

    </div>

</div>

<!-- PAUSE -->

<div id="pauseScreen"
     class="overlay hidden">

    <div class="panel">

        <div class="title"
             style="font-size:50px">
            PAUSED
        </div>

        <p class="subtitle">
            The arena is frozen. Enemies, bullets, damage, timers and spawning are stopped.
        </p>

        <div class="pauseStats">
            <div class="pauseStat"><small>SCORE</small><strong id="pauseScore">0</strong></div>
            <div class="pauseStat"><small>KILLS</small><strong id="pauseKills">0</strong></div>
            <div class="pauseStat"><small>WAVE</small><strong id="pauseWave">1</strong></div>
        </div>

        <button id="resumeButton">RESUME</button>
        <button id="pauseRestartButton" class="secondaryButton">RESTART RUN</button>

    </div>

</div>

</div>


<script>

/* =========================================================
   CANVAS SETUP
========================================================= */

const canvas =
    document.getElementById("gameCanvas");

const ctx =
    canvas.getContext("2d");

let width;
let height;

function resizeCanvas() {

    width =
        canvas.width =
        window.innerWidth;

    height =
        canvas.height =
        window.innerHeight;
}

resizeCanvas();

window.addEventListener(
    "resize",
    resizeCanvas
);


/* =========================================================
   GAME STATE
========================================================= */

let gameRunning = false;

let paused = false;

let score = 0;

let coins = 0;

let kills = 0;

let wave = 1;

let level = 1;

let xp = 0;

let xpNeeded = 100;

let gameTime = 0;

let waveTimer = 0;

let spawnTimer = 0;

let bossActive = false;
let campaignWon = false;
let waveClearing = false;

/* Professional combat state */
let shieldRegenDelay = 0;
let damageFlashTimer = 0;
let messageTimer = null;
const MAX_WAVES = 10;
const WAVE_DURATION_MS = 30000;
const INITIAL_ENEMIES = 5;
const SPAWN_GRACE_MS = 3000;
const SHIELD_REGEN_DELAY_MS = 2200;
const SHIELD_REGEN_PER_SECOND = 6;
const CONTACT_DAMAGE_COOLDOWN_MS = 500;
const WAVE_CONFIG = [
    null,
    { interval: 1850, maxEnemies: 10, difficulty: 1.00, spawnBatch: 1, eliteChance: 0.00, bosses: 0 },
    { interval: 1650, maxEnemies: 13, difficulty: 1.18, spawnBatch: 1, eliteChance: 0.02, bosses: 0 },
    { interval: 1450, maxEnemies: 16, difficulty: 1.38, spawnBatch: 1, eliteChance: 0.04, bosses: 0 },
    { interval: 1200, maxEnemies: 20, difficulty: 1.62, spawnBatch: 2, eliteChance: 0.07, bosses: 0 },
    { interval: 1050, maxEnemies: 24, difficulty: 1.86, spawnBatch: 2, eliteChance: 0.10, bosses: 1 },
    { interval: 930,  maxEnemies: 27, difficulty: 2.08, spawnBatch: 2, eliteChance: 0.13, bosses: 0 },
    { interval: 820,  maxEnemies: 31, difficulty: 2.32, spawnBatch: 2, eliteChance: 0.17, bosses: 0 },
    { interval: 820,  maxEnemies: 31, difficulty: 2.32, spawnBatch: 2, eliteChance: 0.17, bosses: 0 },
    { interval: 850,  maxEnemies: 30, difficulty: 2.25, spawnBatch: 2, eliteChance: 0.16, bosses: 1 },
    { interval: 900, maxEnemies: 24, difficulty: 2.15, spawnBatch: 2, eliteChance: 0.14, bosses: 1, final: true }
];

let bossesSpawnedThisWave = 0;
let wavePhase = "assault";
let finalBossSpawned = false;
let finalBossDefeated = false;


/* =========================================================
   PLAYER
========================================================= */

const player = {

    x: 0,

    y: 0,

    radius: 18,

    speed: 4.5,

    health: 100,

    maxHealth: 100,

    shield: 50,

    maxShield: 50,

    damage: 20,

    fireRate: 135,

    lastShot: 0,

    bulletSpeed: 14,

    level: 1,

    invincible: 0,

    lastShot: 0,

    shieldRegenDelay: 0,

    rapidFire: 0,

    magnet: 0
};


/* =========================================================
   ARRAYS
========================================================= */

let bullets = [];

let enemies = [];

let particles = [];

let powerUps = [];

let floatingTexts = [];

let stars = [];


/* =========================================================
   INPUT
========================================================= */

const keys = {};

const mouse = {

    x: width / 2,

    y: height / 2,

    down: false

};


window.addEventListener(
    "keydown",
    e => {

        keys[e.key.toLowerCase()] = true;

        if (e.key.toLowerCase() === "p") {

            togglePause();

        }

    }
);

window.addEventListener(
    "keyup",
    e => {

        keys[e.key.toLowerCase()] = false;

    }
);


canvas.addEventListener(
    "mousemove",
    e => {

        mouse.x = e.clientX;

        mouse.y = e.clientY;

    }
);

canvas.addEventListener(
    "mousedown",
    () => {

        mouse.down = true;

    }
);

window.addEventListener(
    "mouseup",
    () => {

        mouse.down = false;

    }
);


/* =========================================================
   AUDIO
========================================================= */

let audioContext;

function initAudio() {

    if (!audioContext) {

        audioContext =
            new (
                window.AudioContext ||
                window.webkitAudioContext
            )();

    }

}

function sound(
    frequency,
    duration,
    type = "sine"
) {

    if (!audioContext)
        return;

    const oscillator =
        audioContext.createOscillator();

    const gain =
        audioContext.createGain();

    oscillator.type = type;

    oscillator.frequency.value =
        frequency;

    gain.gain.setValueAtTime(
        0.06,
        audioContext.currentTime
    );

    gain.gain.exponentialRampToValueAtTime(
        0.001,
        audioContext.currentTime + duration
    );

    oscillator.connect(gain);

    gain.connect(audioContext.destination);

    oscillator.start();

    oscillator.stop(
        audioContext.currentTime + duration
    );
}


/* =========================================================
   BACKGROUND STARS
========================================================= */

function createStars() {

    stars = [];

    for (
        let i = 0;
        i < 180;
        i++
    ) {

        stars.push({

            x:
                Math.random() * width,

            y:
                Math.random() * height,

            size:
                Math.random() * 2 + .3,

            speed:
                Math.random() * .5 + .1,

            alpha:
                Math.random()

        });

    }

}

createStars();


/* =========================================================
   RESET GAME
========================================================= */

function resetGame() {

    score = 0;
    coins = 0;
    kills = 0;
    wave = 1;
    level = 1;
    xp = 0;
    xpNeeded = 100;
    gameTime = 0;
    waveTimer = 0;
    spawnTimer = -SPAWN_GRACE_MS;
    bossActive = false;
    bossesSpawnedThisWave = 0;
    wavePhase = "assault";
    finalBossSpawned = false;
    finalBossDefeated = false;
    campaignWon = false;
    waveClearing = false;
    shieldRegenDelay = 0;
    damageFlashTimer = 0;

    bullets = [];
    enemies = [];
    particles = [];
    powerUps = [];
    floatingTexts = [];

    /* Clear held input so restart can never inherit movement/fire. */
    for (const key in keys) delete keys[key];
    mouse.down = false;
    mouse.x = width / 2;
    mouse.y = height / 2;

    player.x = width / 2;
    player.y = height / 2;
    player.health = 100;
    player.maxHealth = 100;
    player.shield = 50;
    player.maxShield = 50;
    player.damage = 20;
    player.speed = 4.5;
    player.fireRate = 180;
    player.bulletSpeed = 10;
    player.invincible = 0;
    player.lastShot = 0;
    player.shieldRegenDelay = 0;
    player.rapidFire = 0;
    player.magnet = 0;

    /* Controlled opening: only a handful of enemies, never a swarm. */
    for (let i = 0; i < INITIAL_ENEMIES; i++) spawnEnemy(true);
    updateHUD();
}


/* =========================================================
   DRAW BACKGROUND
========================================================= */

function drawBackground() {

    ctx.fillStyle =
        "#030510";

    ctx.fillRect(
        0,
        0,
        width,
        height
    );


    /* Grid */

    ctx.strokeStyle =
        "rgba(0,255,255,.045)";

    ctx.lineWidth = 1;

    const gridSize = 50;

    const offset =
        (gameTime * 0.03) %
        gridSize;

    for (
        let x = -gridSize + offset;
        x < width;
        x += gridSize
    ) {

        ctx.beginPath();

        ctx.moveTo(
            x,
            0
        );

        ctx.lineTo(
            x,
            height
        );

        ctx.stroke();

    }

    for (
        let y = -gridSize + offset;
        y < height;
        y += gridSize
    ) {

        ctx.beginPath();

        ctx.moveTo(
            0,
            y
        );

        ctx.lineTo(
            width,
            y
        );

        ctx.stroke();

    }


    /* Stars */

    for (const star of stars) {

        if (gameRunning && !paused) star.y += star.speed;

        if (star.y > height)
            star.y = 0;

        ctx.globalAlpha =
            star.alpha;

        ctx.fillStyle =
            "#9be7ff";

        ctx.beginPath();

        ctx.arc(
            star.x,
            star.y,
            star.size,
            0,
            Math.PI * 2
        );

        ctx.fill();

    }

    ctx.globalAlpha = 1;

}


/* =========================================================
   PLAYER MOVEMENT
========================================================= */

function updatePlayer(delta) {

    let dx = 0;
    let dy = 0;

    if (keys["w"] || keys["arrowup"]) dy--;
    if (keys["s"] || keys["arrowdown"]) dy++;
    if (keys["a"] || keys["arrowleft"]) dx--;
    if (keys["d"] || keys["arrowright"]) dx++;

    if (dx !== 0 || dy !== 0) {
        const length = Math.hypot(dx, dy);
        dx /= length;
        dy /= length;
        player.x += dx * player.speed * delta * 60;
        player.y += dy * player.speed * delta * 60;
    }

    player.x = Math.max(player.radius, Math.min(width - player.radius, player.x));
    player.y = Math.max(player.radius, Math.min(height - player.radius, player.y));

    player.invincible = Math.max(0, player.invincible - delta * 1000);
    player.rapidFire = Math.max(0, player.rapidFire - delta * 1000);
    player.magnet = Math.max(0, player.magnet - delta * 1000);
    shieldRegenDelay = Math.max(0, shieldRegenDelay - delta * 1000);

    /* Shield regeneration is delayed after damage and cannot exceed max. */
    if (shieldRegenDelay <= 0 && player.shield < player.maxShield) {
        player.shield = Math.min(
            player.maxShield,
            player.shield + SHIELD_REGEN_PER_SECOND * delta
        );
    }
}


/* =========================================================
   SHOOT
========================================================= */

function shoot() {

    if (!gameRunning || paused) return;

    const now = performance.now();
    let rate = player.fireRate;
    if (player.rapidFire > 0) rate /= 2;
    if (now - player.lastShot < rate) return;

    player.lastShot = now;

    const angle = Math.atan2(
        mouse.y - player.y,
        mouse.x - player.x
    );

    if (level >= 5) {
        [-0.15, 0, 0.15].forEach(offset => createBullet(angle + offset));
    } else {
        createBullet(angle);
    }

    sound(400, .06, "square");
}


/* =========================================================
   CREATE BULLET
========================================================= */

function createBullet(angle) {

    bullets.push({

        x: player.x,

        y: player.y,

        vx:
            Math.cos(angle) *
            player.bulletSpeed,

        vy:
            Math.sin(angle) *
            player.bulletSpeed,

        radius: 5,

        damage: player.damage,

        life: 1500

    });

}


/* =========================================================
   UPDATE BULLETS
========================================================= */

function updateBullets(delta) {

    for (
        let i = bullets.length - 1;
        i >= 0;
        i--
    ) {

        const b =
            bullets[i];

        b.x +=
            b.vx *
            delta *
            60;

        b.y +=
            b.vy *
            delta *
            60;

        b.life -=
            delta * 1000;


        if (
            b.life <= 0 ||
            b.x < -50 ||
            b.x > width + 50 ||
            b.y < -50 ||
            b.y > height + 50
        ) {

            bullets.splice(i, 1);

            continue;

        }


        /* Collision */

        for (
            let j = enemies.length - 1;
            j >= 0;
            j--
        ) {

            const enemy =
                enemies[j];

            if (!enemy || enemy.health <= 0) continue;

            const dx =
                b.x - enemy.x;

            const dy =
                b.y - enemy.y;

            const dist =
                Math.sqrt(
                    dx * dx +
                    dy * dy
                );


            if (
                dist <
                b.radius +
                enemy.radius
            ) {

                const hitDamage = enemy.finalBoss ? b.damage * 3.0 : b.damage;
                enemy.health = Math.max(0, enemy.health - hitDamage);

                createExplosion(
                    b.x,
                    b.y,
                    4,
                    "#00f7ff"
                );

                bullets.splice(i, 1);

                if (
                    enemy.health <= 0
                ) {

                    killEnemy(
                        enemy
                    );

                    enemies.splice(
                        j,
                        1
                    );
                    if (wavePhase === "cleanup" && countLivingEnemies() === 0) finishWave();
                    resolveFinalWaveCompletion();

                }

                break;

            }

        }

    }

}


/* =========================================================
   SPAWN ENEMY
========================================================= */

function currentEnemyCap() {
    const config = WAVE_CONFIG[Math.min(wave, MAX_WAVES)];
    return config ? config.maxEnemies : 0;
}

function spawnEnemy(initial = false, forcedType = null) {
    const config = WAVE_CONFIG[Math.min(wave, MAX_WAVES)];
    if (!config) return false;

    const allowed = initial ? INITIAL_ENEMIES : config.maxEnemies;
    if (enemies.length >= allowed) return false;

    const margin = 55;
    let x, y;
    let attempts = 0;

    do {
        const side = Math.floor(Math.random() * 4);
        if (side === 0) { x = -margin; y = Math.random() * height; }
        else if (side === 1) { x = width + margin; y = Math.random() * height; }
        else if (side === 2) { x = Math.random() * width; y = -margin; }
        else { x = Math.random() * width; y = height + margin; }
        attempts++;
    } while (Math.hypot(x - player.x, y - player.y) < 430 && attempts < 25);

    const roll = Math.random();
    let type;
    if (forcedType) {
        type = forcedType;
    } else if (roll < config.eliteChance) {
        type = "elite";
    } else if (roll < 0.48) {
        type = "drone";
    } else if (roll < 0.70) {
        type = "tank";
    } else if (roll < 0.88) {
        type = "hunter";
    } else {
        type = "splitter";
    }

    const d = config.difficulty;
    const baseHealth = 58 * Math.pow(d, 1.28);
    const enemy = {
        x, y, type, radius: 16, speed: 1.25 + wave * 0.025,
        health: baseHealth, maxHealth: baseHealth,
        damage: 8.5 * Math.pow(d, 1.05), color: "#ff315f", score: 12 + wave * 2, xp: 16 + wave * 2,
        hitCooldown: 0, initial, dead: false, elite: false
    };

    if (type === "tank") {
        enemy.radius = 27; enemy.speed = 0.62 + Math.min(.45, wave * .035);
        enemy.health *= 3.4; enemy.maxHealth = enemy.health;
        enemy.damage *= 1.65; enemy.color = "#ff8a00";
        enemy.score += 25; enemy.xp += 25;
    } else if (type === "hunter") {
        enemy.radius = 13; enemy.speed = 2.45 + Math.min(.75, wave * .05);
        enemy.health *= .78; enemy.maxHealth = enemy.health;
        enemy.damage *= 1.18; enemy.color = "#bf5cff";
        enemy.score += 22; enemy.xp += 20;
    } else if (type === "splitter") {
        enemy.radius = 20; enemy.speed = 1.05 + Math.min(.45, wave * .03);
        enemy.health *= 1.75; enemy.maxHealth = enemy.health;
        enemy.damage *= 1.25; enemy.color = "#00ffa6";
        enemy.score += 35; enemy.xp += 30;
    } else if (type === "elite") {
        enemy.radius = 23; enemy.speed = 1.45 + Math.min(.5, wave * .035);
        enemy.health *= 2.65; enemy.maxHealth = enemy.health;
        enemy.damage *= 1.9; enemy.color = "#ffe600";
        enemy.score += 80; enemy.xp += 65;
        enemy.elite = true;
    }

    enemies.push(enemy);
    return true;
}

/* =========================================================
   SPAWN BOSS
========================================================= */

function spawnBoss(forceFinal = false) {
    const config = WAVE_CONFIG[Math.min(wave, MAX_WAVES)];
    if (!config) return false;
    if (bossesSpawnedThisWave >= config.bosses) return false;

    const isFinal = forceFinal || !!config.final || wave === MAX_WAVES;
    bossesSpawnedThisWave++;
    bossActive = true;

    const bossIndex = bossesSpawnedThisWave;
    /* Final encounter is deliberately tuned for a clear, beatable finish. */
    const health = isFinal
        ? 6200
        : (4800 + wave * 900);

    const boss = {
        x: width * (0.25 + bossIndex * 0.25),
        y: -120,
        type: "boss",
        radius: isFinal ? (bossIndex === 1 ? 82 : 68) : 66,
        speed: isFinal ? .62 : .48,
        health, maxHealth: health,
        damage: isFinal ? 24 : 28,
        color: isFinal ? (bossIndex === 1 ? "#ff1744" : "#ff00cc") : "#ff00cc",
        score: isFinal ? 3500 : 1400,
        xp: isFinal ? 1100 : 650,
        boss: true,
        finalBoss: isFinal,
        bossIndex,
        hitCooldown: 0,
        dead: false
    };

    enemies.push(boss);
    if (isFinal && bossIndex === 1) finalBossSpawned = true;

    showPowerMessage(
        isFinal
            ? (bossIndex === 1 ? "☠ FINAL BOSS + ARMY DEPLOYED ☠" : "☠ BOSS REINFORCEMENT DEPLOYED ☠")
            : "⚠ BOSS INCOMING ⚠"
    );
    sound(isFinal ? 45 : 65, .85, "sawtooth");
    return true;
}

/* =========================================================
   UPDATE ENEMIES
========================================================= */

function updateEnemies(delta) {

    for (let i = enemies.length - 1; i >= 0; i--) {
        const enemy = enemies[i];
        if (!enemy || enemy.dead) continue;

        enemy.hitCooldown = Math.max(0, (enemy.hitCooldown || 0) - delta * 1000);

        const dx = player.x - enemy.x;
        const dy = player.y - enemy.y;
        const dist = Math.hypot(dx, dy) || 1;

        if (dist > player.radius + enemy.radius) {
            const step = enemy.speed * delta * 60;
            enemy.x += dx / dist * step;
            enemy.y += dy / dist * step;
        }

        enemy.x = Math.max(-enemy.radius, Math.min(width + enemy.radius, enemy.x));
        enemy.y = Math.max(-enemy.radius, Math.min(height + enemy.radius, enemy.y));

        if (enemy.boss) {
            enemy.x += Math.sin(gameTime * .001) * .7;
        }

        const currentDistance = Math.hypot(player.x - enemy.x, player.y - enemy.y);
        if (currentDistance <= player.radius + enemy.radius + 2 && enemy.hitCooldown <= 0) {
            enemy.hitCooldown = CONTACT_DAMAGE_COOLDOWN_MS;
            damagePlayer(enemy.damage);
        }
    }

    /* Prevent enemies from occupying exactly the same pixel. */
    for (let i = 0; i < enemies.length; i++) {
        const a = enemies[i];
        if (!a || a.dead) continue;
        for (let j = i + 1; j < enemies.length; j++) {
            const b = enemies[j];
            if (!b || b.dead) continue;
            const dx = b.x - a.x, dy = b.y - a.y;
            const d = Math.hypot(dx, dy) || .001;
            const min = a.radius + b.radius + 2;
            if (d < min) {
                const push = (min - d) * .5;
                a.x -= dx / d * push; a.y -= dy / d * push;
                b.x += dx / d * push; b.y += dy / d * push;
            }
        }
    }
}


/* =========================================================
   DAMAGE PLAYER
========================================================= */

function damagePlayer(amount) {

    if (!gameRunning || paused || player.invincible > 0) return;

    amount = Number(amount);
    if (!Number.isFinite(amount) || amount <= 0) return;

    player.invincible = 140;
    shieldRegenDelay = SHIELD_REGEN_DELAY_MS;
    player.shieldRegenDelay = SHIELD_REGEN_DELAY_MS;

    const incoming = Math.max(0, amount);
    const absorbed = Math.min(player.shield, incoming);
    player.shield = Math.max(0, player.shield - absorbed);

    const healthDamage = Math.max(0, incoming - absorbed);
    player.health = Math.max(0, Math.min(player.maxHealth, player.health - healthDamage));

    damageFlashTimer = 140;
    createExplosion(player.x, player.y, 8, "#ff315f");
    sound(100, .15, "sawtooth");

    if (player.health <= 0) endGame();
}


/* =========================================================
   KILL ENEMY
========================================================= */

function killEnemy(enemy) {

    kills++;

    score += enemy.score;

    coins +=
        Math.floor(
            enemy.score / 5
        );

    addXP(enemy.xp);


    createExplosion(
        enemy.x,
        enemy.y,
        enemy.boss ? 40 : 15,
        enemy.color
    );


    floatingTexts.push({

        x: enemy.x,

        y: enemy.y,

        text:
            "+" +
            enemy.score,

        color:
            "#ffe600",

        life: 1000

    });


    sound(
        enemy.boss ? 60 : 180,
        enemy.boss ? .7 : .1,
        "triangle"
    );


    /* Splitter creates smaller enemies */

    if (
        enemy.type ===
        "splitter"
    ) {

        for (
            let i = 0;
            i < 3 && enemies.length < currentEnemyCap();
            i++
        ) {

            enemies.push({

                x:
                    enemy.x +
                    (Math.random() * 50 - 25),

                y:
                    enemy.y +
                    (Math.random() * 50 - 25),

                type: "mini",

                radius: 9,

                speed: 2 + Math.min(.8, wave * .04),

                health: 24 + wave * 4,

                maxHealth: 24 + wave * 4,

                damage: 6 + wave * .6,

                color: "#5cffc8",

                score: 5,

                xp: 5

            });

        }

    }


    /* The final boss death starts a locked arena-clear phase: no more spawns. */
    if (enemy.finalBoss && wave === MAX_WAVES) {
        finalBossDefeated = true;
        wavePhase = "cleanup";
        waveClearing = true;
        spawnTimer = 0;
        showPowerMessage("☠ FINAL BOSS DEFEATED — CLEAR THE ARENA! ☠");
    }

    /* Boss reward */

    if (enemy.boss) {
        bossActive = enemies.some(e => e && e.boss && !e.dead && e !== enemy);
        const reward = enemy.finalBoss ? 750 : 350;
        coins += reward;
        score += enemy.finalBoss ? 3500 : 1200;

        /* Bosses give a recovery window, but not a full free heal on every kill. */
        player.health = Math.min(player.maxHealth, player.health + (enemy.finalBoss ? 45 : 25));
        player.shield = Math.min(player.maxShield, player.shield + (enemy.finalBoss ? 35 : 20));

        showPowerMessage(
            enemy.finalBoss ? "☠ BOSS DESTROYED! +" + reward + " COINS" : "👑 BOSS DEFEATED! +" + reward + " COINS"
        );
    }


    /* Chance of powerup */

    if (
        Math.random() <
        .12
    ) {

        createPowerUp(
            enemy.x,
            enemy.y
        );

    }

}


/* =========================================================
   XP
========================================================= */

function addXP(amount) {

    xp += amount;


    while (
        xp >= xpNeeded
    ) {

        xp -= xpNeeded;

        levelUp();

    }

}


/* =========================================================
   LEVEL UP
========================================================= */

function levelUp() {

    level++;

    xpNeeded =
        Math.floor(
            xpNeeded * 1.48
        );


    player.maxHealth += 8;

    player.health =
        player.maxHealth;

    player.maxShield += 5;

    player.shield =
        player.maxShield;

    player.damage += 4;

    player.speed += .15;

    player.fireRate =
        Math.max(
            68,
            player.fireRate - 7
        );


    showPowerMessage(
        "LEVEL UP!  ⚡ " +
        level
    );


    createExplosion(
        player.x,
        player.y,
        35,
        "#00f7ff"
    );


    sound(
        700,
        .4,
        "sine"
    );

}


/* =========================================================
   POWERUPS
========================================================= */

function createPowerUp(
    x,
    y
) {

    const types = [

        "health",

        "shield",

        "rapid",

        "magnet",

        "coin"

    ];


    const type =
        types[
            Math.floor(
                Math.random() *
                types.length
            )
        ];


    powerUps.push({

        x: x,

        y: y,

        type: type,

        radius: 12,

        life: 12000

    });

}


/* =========================================================
   UPDATE POWERUPS
========================================================= */

function updatePowerUps(delta) {

    for (
        let i =
            powerUps.length - 1;
        i >= 0;
        i--
    ) {

        const p =
            powerUps[i];


        p.life -=
            delta * 1000;


        let dx =
            player.x - p.x;

        let dy =
            player.y - p.y;


        const dist =
            Math.sqrt(
                dx * dx +
                dy * dy
            );


        if (
            player.magnet > 0 &&
            dist < 250
        ) {

            if (dist > 0.001) {
                p.x += dx / dist * 5;
                p.y += dy / dist * 5;
            }

        }


        if (
            dist <
            player.radius +
            p.radius
        ) {

            collectPowerUp(p);

            powerUps.splice(
                i,
                1
            );

            continue;

        }


        if (
            p.life <= 0
        ) {

            powerUps.splice(
                i,
                1
            );

        }

    }

}


/* =========================================================
   COLLECT POWERUP
========================================================= */

function collectPowerUp(p) {

    if (p.type === "health") {

        player.health =
            Math.min(
                player.maxHealth,
                player.health + 35
            );

        showPowerMessage(
            "❤️ HEALTH +35"
        );

    }


    if (p.type === "shield") {

        player.shield =
            Math.min(
                player.maxShield,
                player.shield + 40
            );

        showPowerMessage(
            "🛡 SHIELD +40"
        );

    }


    if (p.type === "rapid") {

        player.rapidFire =
            7000;

        showPowerMessage(
            "⚡ RAPID FIRE!"
        );

    }


    if (p.type === "magnet") {

        player.magnet =
            10000;

        showPowerMessage(
            "🧲 XP MAGNET!"
        );

    }


    if (p.type === "coin") {

        coins += 50;

        score += 100;

        showPowerMessage(
            "💰 +50 COINS"
        );

    }


    sound(
        900,
        .2,
        "sine"
    );

}


/* =========================================================
   PARTICLES
========================================================= */

function createExplosion(
    x,
    y,
    count,
    color
) {

    for (
        let i = 0;
        i < count;
        i++
    ) {

        const angle =
            Math.random() *
            Math.PI *
            2;

        const speed =
            Math.random() *
            5 +
            1;

        particles.push({

            x: x,

            y: y,

            vx:
                Math.cos(angle) *
                speed,

            vy:
                Math.sin(angle) *
                speed,

            life:
                Math.random() *
                700 +
                300,

            maxLife: 1000,

            size:
                Math.random() *
                4 +
                1,

            color: color

        });

    }

}


/* =========================================================
   UPDATE PARTICLES
========================================================= */

function updateParticles(delta) {

    for (
        let i =
            particles.length - 1;
        i >= 0;
        i--
    ) {

        const p =
            particles[i];

        p.x +=
            p.vx *
            delta *
            60;

        p.y +=
            p.vy *
            delta *
            60;

        p.vx *= .97;

        p.vy *= .97;

        p.life -=
            delta * 1000;


        if (p.life <= 0) {

            particles.splice(
                i,
                1
            );

        }

    }

}


/* =========================================================
   DRAW PLAYER
========================================================= */

function drawPlayer() {

    ctx.save();

    ctx.translate(
        player.x,
        player.y
    );


    /* Shield */

    if (player.shield > 0) {

        ctx.beginPath();

        ctx.arc(
            0,
            0,
            27,
            0,
            Math.PI * 2
        );

        ctx.strokeStyle =
            "rgba(0,255,255,.55)";

        ctx.lineWidth = 3;

        ctx.shadowBlur = 15;

        ctx.shadowColor =
            "#00ffff";

        ctx.stroke();

    }


    /* Aim direction */

    const angle =
        Math.atan2(
            mouse.y - player.y,
            mouse.x - player.x
        );

    ctx.rotate(angle);


    /* Ship */

    ctx.beginPath();

    ctx.moveTo(
        24,
        0
    );

    ctx.lineTo(
        -15,
        -13
    );

    ctx.lineTo(
        -9,
        0
    );

    ctx.lineTo(
        -15,
        13
    );

    ctx.closePath();


    ctx.fillStyle =
        "#00f7ff";

    ctx.shadowBlur = 20;

    ctx.shadowColor =
        "#00f7ff";

    ctx.fill();


    /* Engine */

    ctx.beginPath();

    ctx.moveTo(
        -10,
        -6
    );

    ctx.lineTo(
        -25,
        0
    );

    ctx.lineTo(
        -10,
        6
    );

    ctx.fillStyle =
        "#ff00cc";

    ctx.shadowColor =
        "#ff00cc";

    ctx.fill();


    ctx.restore();

}


/* =========================================================
   DRAW BULLETS
========================================================= */

function drawBullets() {

    for (const b of bullets) {

        ctx.beginPath();

        ctx.arc(
            b.x,
            b.y,
            b.radius,
            0,
            Math.PI * 2
        );

        ctx.fillStyle =
            "#00ffff";

        ctx.shadowBlur = 15;

        ctx.shadowColor =
            "#00ffff";

        ctx.fill();

    }

    ctx.shadowBlur = 0;

}


/* =========================================================
   DRAW ENEMIES
========================================================= */

function drawEnemies() {

    for (const enemy of enemies) {

        ctx.save();

        ctx.translate(
            enemy.x,
            enemy.y
        );


        /* Elite aura */
        if (enemy.elite) {
            ctx.beginPath();
            ctx.arc(0, 0, enemy.radius + 9 + Math.sin(gameTime * .008) * 2, 0, Math.PI * 2);
            ctx.strokeStyle = "rgba(255,230,0,.45)";
            ctx.lineWidth = 3;
            ctx.shadowBlur = 20;
            ctx.shadowColor = "#ffe600";
            ctx.stroke();
        }

        /* Boss aura */

        if (enemy.boss) {

            ctx.beginPath();

            ctx.arc(
                0,
                0,
                enemy.radius + 15,
                0,
                Math.PI * 2
            );

            ctx.strokeStyle =
                "rgba(255,0,200,.4)";

            ctx.lineWidth = 5;

            ctx.shadowBlur = 30;

            ctx.shadowColor =
                "#ff00cc";

            ctx.stroke();

        }


        /* Enemy body */

        ctx.beginPath();

        ctx.arc(
            0,
            0,
            enemy.radius,
            0,
            Math.PI * 2
        );

        ctx.fillStyle =
            enemy.color;

        ctx.shadowBlur =
            enemy.boss ? 30 : 15;

        ctx.shadowColor =
            enemy.color;

        ctx.fill();


        /* Core */

        ctx.beginPath();

        ctx.arc(
            0,
            0,
            enemy.radius * .35,
            0,
            Math.PI * 2
        );

        ctx.fillStyle =
            "#080b20";

        ctx.fill();


        /* Health bar */

        const barWidth =
            enemy.radius * 2;

        const healthPercent =
            enemy.health /
            enemy.maxHealth;


        ctx.fillStyle =
            "rgba(0,0,0,.5)";

        ctx.fillRect(
            -barWidth / 2,
            -enemy.radius - 9,
            barWidth,
            4
        );


        ctx.fillStyle =
            "#00ff88";

        ctx.fillRect(
            -barWidth / 2,
            -enemy.radius - 9,
            barWidth *
            Math.max(
                0,
                healthPercent
            ),
            4
        );


        ctx.restore();

    }

}


/* =========================================================
   DRAW POWERUPS
========================================================= */

function drawPowerUps() {

    for (const p of powerUps) {

        let color;

        let symbol;


        if (p.type === "health") {

            color = "#ff315f";
            symbol = "♥";

        }

        else if (
            p.type === "shield"
        ) {

            color = "#00ffff";
            symbol = "◆";

        }

        else if (
            p.type === "rapid"
        ) {

            color = "#ffe600";
            symbol = "⚡";

        }

        else if (
            p.type === "magnet"
        ) {

            color = "#bf5cff";
            symbol = "M";

        }

        else {

            color = "#00ff88";
            symbol = "$";

        }


        ctx.beginPath();

        ctx.arc(
            p.x,
            p.y,
            p.radius +
            Math.sin(
                gameTime * .005
            ) * 3,
            0,
            Math.PI * 2
        );

        ctx.fillStyle =
            color;

        ctx.shadowBlur = 20;

        ctx.shadowColor =
            color;

        ctx.fill();


        ctx.fillStyle =
            "#050716";

        ctx.font =
            "bold 12px Arial";

        ctx.textAlign =
            "center";

        ctx.textBaseline =
            "middle";

        ctx.fillText(
            symbol,
            p.x,
            p.y
        );

    }

}


/* =========================================================
   DRAW PARTICLES
========================================================= */

function drawParticles() {

    for (const p of particles) {

        ctx.globalAlpha =
            Math.max(
                0,
                p.life /
                p.maxLife
            );

        ctx.beginPath();

        ctx.arc(
            p.x,
            p.y,
            p.size,
            0,
            Math.PI * 2
        );

        ctx.fillStyle =
            p.color;

        ctx.fill();

    }

    ctx.globalAlpha = 1;

}


/* =========================================================
   FLOATING TEXT
========================================================= */

function updateFloatingTexts(
    delta
) {

    for (
        let i =
            floatingTexts.length - 1;
        i >= 0;
        i--
    ) {

        const t =
            floatingTexts[i];

        t.y -=
            delta * 30;

        t.life -=
            delta * 1000;


        if (t.life <= 0) {

            floatingTexts.splice(
                i,
                1
            );

        }

    }

}


function drawFloatingTexts() {

    for (const t of floatingTexts) {

        ctx.globalAlpha =
            t.life / 1000;

        ctx.fillStyle =
            t.color;

        ctx.font =
            "bold 15px Arial";

        ctx.textAlign =
            "center";

        ctx.fillText(
            t.text,
            t.x,
            t.y
        );

    }

    ctx.globalAlpha = 1;

}


/* =========================================================
   WAVE SYSTEM
========================================================= */

function countLivingEnemies() {
    let count = 0;
    for (const e of enemies) if (e && !e.dead && e.health > 0) count++;
    return count;
}

function finishWave() {
    if (campaignWon || wavePhase !== "cleanup") return;

    /* Hard guard: wave 10 can NEVER advance to wave 11. */
    if (wave >= MAX_WAVES) {
        wave = MAX_WAVES;
        wavePhase = "victory";
        waveClearing = true;
        completeCampaign();
        return;
    }

    wave++;
    waveTimer = 0;
    spawnTimer = -1800;
    bossesSpawnedThisWave = 0;
    bossActive = false;
    wavePhase = "assault";
    waveClearing = false;

    /* Late-wave combat boosts: stronger player weapons keep Waves 9-10 manageable. */
    if (wave === 9) {
        player.damage += 7;
        player.fireRate = Math.max(62, player.fireRate - 18);
        player.bulletSpeed += 2;
        player.shield = Math.min(player.maxShield, player.shield + 20);
        player.health = Math.min(player.maxHealth, player.health + 15);
        showPowerMessage("⚡ WAVE 9 BOOST — RAPID FIRE ONLINE ⚡");
        sound(650, .20, "sawtooth");
    } else if (wave === MAX_WAVES) {
        player.damage += 9;
        player.fireRate = Math.max(52, player.fireRate - 22);
        player.bulletSpeed += 2.5;
        player.shield = Math.min(player.maxShield, player.shield + 30);
        player.health = Math.min(player.maxHealth, player.health + 25);
        showPowerMessage("⚡ FINAL WAVE BOOST — OVERDRIVE WEAPONS ONLINE ⚡");
        sound(720, .22, "sawtooth");
    } else {
        showPowerMessage("WAVE " + wave + " / " + MAX_WAVES + " — PRESSURE INCREASING");
    }
    sound(220 + wave * 35, .28, "triangle");
}

function resolveFinalWaveCompletion() {
    if (campaignWon || wave !== MAX_WAVES || wavePhase !== "cleanup") return false;
    if (!finalBossSpawned || !finalBossDefeated) return false;
    if (countLivingEnemies() !== 0) return false;
    completeCampaign();
    return true;
}

function updateWave(delta) {
    if (campaignWon || wavePhase === "victory") return;

    waveTimer += delta * 1000;
    const config = WAVE_CONFIG[wave];
    if (!config) return;

    /*
       Wave 10 completion watchdog.
       The old flow depended on one particular enemy-removal path calling
       finishWave(). If the last enemy was removed during another update
       stage, the HUD could remain on 10/10 with 0 enemies. This watchdog
       checks the real living-enemy state every frame and guarantees that
       the campaign terminal state is reached.
    */
    if (resolveFinalWaveCompletion()) return;

    /* Wave 10 uses one clearly readable final boss; no hidden reinforcements. */
    if (config.final && waveTimer >= 5000 && bossesSpawnedThisWave < config.bosses) {
        spawnBoss(true);
    }

    if (!config.final && config.bosses > 0 && waveTimer >= WAVE_DURATION_MS * .55 && bossesSpawnedThisWave < config.bosses) {
        spawnBoss(false);
    }

    if (waveTimer >= WAVE_DURATION_MS) {
        wavePhase = "cleanup";
        waveClearing = true;

        /*
           Never silently stop at 10/10. Ensure the configured final boss
           has spawned before evaluating the arena-clear state.
        */
        if (wave === MAX_WAVES && bossesSpawnedThisWave < config.bosses) {
            while (bossesSpawnedThisWave < config.bosses) {
                spawnBoss(true);
            }
        }

        if (wave === MAX_WAVES) {
            resolveFinalWaveCompletion();
        } else if (countLivingEnemies() === 0 && bossesSpawnedThisWave >= config.bosses) {
            finishWave();
        }
    }
}

function updateSpawning(delta) {
    if (waveClearing || campaignWon || wavePhase !== "assault") return;

    spawnTimer += delta * 1000;
    if (gameTime < SPAWN_GRACE_MS) return;

    const config = WAVE_CONFIG[wave];
    if (!config || enemies.length >= config.maxEnemies) return;
    if (spawnTimer < config.interval) return;
    spawnTimer = 0;

    for (let i = 0; i < config.spawnBatch; i++) {
        if (enemies.length >= config.maxEnemies) break;

        /* The final wave keeps feeding the arena even while bosses are alive. */
        if (config.final && Math.random() < .15) {
            spawnEnemy(false, Math.random() < config.eliteChance ? "elite" : "hunter");
        } else {
            spawnEnemy(false);
        }
    }
}

/* =========================================================
   MESSAGE
========================================================= */

function showPowerMessage(
    text
) {

    const element =
        document.getElementById(
            "powerMessage"
        );

    element.textContent =
        text;

    element.style.opacity =
        "1";

    clearTimeout(
        messageTimer
    );

    messageTimer =
        setTimeout(() => {

            element.style.opacity =
                "0";

        }, 1600);

}


/* =========================================================
   HUD UPDATE
========================================================= */

function updateBossHUD() {
    const hud = document.getElementById("bossHud");
    if (!hud) return;
    const bosses = enemies.filter(e => e && e.boss && e.health > 0);
    if (!bosses.length) {
        hud.classList.add("hidden");
        hud.classList.remove("final");
        return;
    }
    const boss = bosses.slice().sort((a,b) => (b.health / b.maxHealth) - (a.health / a.maxHealth))[0];
    hud.classList.remove("hidden");
    hud.classList.toggle("final", !!boss.finalBoss);
    document.getElementById("bossHudName").textContent = boss.finalBoss ? "FINAL BOSS" : "BOSS";
    document.getElementById("bossHudCount").textContent = bosses.length > 1 ? " • " + bosses.length + " ACTIVE" : "";
    document.getElementById("bossBarFill").style.width = Math.max(0, Math.min(100, boss.health / boss.maxHealth * 100)) + "%";
}

function updateHUD() {

    const health = Math.max(0, Math.min(player.health, player.maxHealth));
    const shield = Math.max(0, Math.min(player.shield, player.maxShield));
    const hpPct = player.maxHealth > 0 ? health / player.maxHealth * 100 : 0;
    const shieldPct = player.maxShield > 0 ? shield / player.maxShield * 100 : 0;
    const xpPct = xpNeeded > 0 ? Math.min(100, xp / xpNeeded * 100) : 0;

    document.getElementById("level").textContent = level;
    document.getElementById("score").textContent = Math.floor(score);
    document.getElementById("coins").textContent = coins;
    document.getElementById("kills").textContent = kills;
    document.getElementById("damage").textContent = Math.floor(player.damage);
    document.getElementById("wave").textContent = wave + " / " + MAX_WAVES;

    document.getElementById("healthText").textContent = Math.ceil(health);
    document.getElementById("healthMaxText").textContent = Math.ceil(player.maxHealth);
    document.getElementById("shieldText").textContent = Math.ceil(shield);
    document.getElementById("shieldMaxText").textContent = Math.ceil(player.maxShield);
    document.getElementById("xpText").textContent = Math.floor(xp);
    document.getElementById("xpNeeded").textContent = xpNeeded;

    document.getElementById("healthBar").style.width = hpPct + "%";
    document.getElementById("shieldBar").style.width = shieldPct + "%";
    document.getElementById("xpBar").style.width = xpPct + "%";

    const totalSeconds = Math.floor(gameTime / 1000);
    const mm = String(Math.floor(totalSeconds / 60)).padStart(2, "0");
    const ss = String(totalSeconds % 60).padStart(2, "0");
    document.getElementById("timeText").textContent = mm + ":" + ss;
    document.getElementById("enemyCount").textContent = enemies.length;
    const progress = Math.min(100, (waveTimer / WAVE_DURATION_MS) * 100);
    const progressFill = document.getElementById("waveProgressFill");
    progressFill.style.width = progress + "%";
    progressFill.style.background = wavePhase === "cleanup" ? "linear-gradient(90deg,#ffe600,#ff6b00)" : (wave === MAX_WAVES ? "linear-gradient(90deg,#ff1744,#ff00cc,#ffe600)" : "linear-gradient(90deg,#00f7ff,#8a2be2,#ff00cc)");
    const status = document.getElementById("statusMessage");
    if (status) {
        status.textContent = wavePhase === "cleanup" ? "ARENA CLEARANCE — ELIMINATE REMAINING HOSTILES" : (wave === MAX_WAVES ? "FINAL WAVE — EXTREME THREAT" : "WAVE " + wave + " / " + MAX_WAVES);
        status.style.opacity = (wavePhase === "cleanup" || wave === MAX_WAVES) ? "1" : "0";
    }
    document.getElementById("pauseScore").textContent = Math.floor(score);
    document.getElementById("pauseKills").textContent = kills;
    document.getElementById("pauseWave").textContent = wave;

    updateBossHUD();

    const flash = document.getElementById("damageFlash");
    flash.style.opacity = damageFlashTimer > 0 ? String(Math.min(.8, damageFlashTimer / 140)) : "0";
}


/* =========================================================
   MAIN UPDATE
========================================================= */

function update(delta) {

    if (campaignWon) {
        updateParticles(delta);
        updateFloatingTexts(delta);
        return;
    }

    if (!gameRunning)
        return;

    if (paused)
        return;


    gameTime +=
        delta * 1000;


    updatePlayer(delta);

    updateBullets(delta);

    updateEnemies(delta);

    updatePowerUps(delta);

    updateParticles(delta);

    updateFloatingTexts(delta);

    updateWave(delta);

    updateSpawning(delta);

    /* Final safety net: completion cannot depend on a specific collision
       or bullet-removal path. */
    resolveFinalWaveCompletion();

    damageFlashTimer = Math.max(0, damageFlashTimer - delta * 1000);

    if (mouse.down)
        shoot();


    updateHUD();

}


/* =========================================================
   DRAW EVERYTHING
========================================================= */

function draw() {

    drawBackground();

    drawPowerUps();

    drawParticles();

    drawBullets();

    drawEnemies();

    drawPlayer();

    drawFloatingTexts();

}


/* =========================================================
   GAME LOOP
========================================================= */

let lastTime =
    performance.now();


function gameLoop(
    currentTime
) {

    const delta =
        Math.min(
            (currentTime -
                lastTime) /
            1000,
            .05
        );

    lastTime =
        currentTime;


    update(delta);

    draw();


    requestAnimationFrame(
        gameLoop
    );

}


requestAnimationFrame(
    gameLoop
);


/* =========================================================
   START GAME
========================================================= */

function startGame() {

    initAudio();

    resetGame();

    gameRunning = true;

    paused = false;
    lastTime = performance.now();


    document
        .getElementById(
            "startScreen"
        )
        .classList
        .add("hidden");


    document
        .getElementById(
            "gameOver"
        )
        .classList
        .add("hidden");


    document
        .getElementById(
            "pauseScreen"
        )
        .classList
        .add("hidden");

    document
        .getElementById("victoryScreen")
        .classList
        .add("hidden");

    document.getElementById("waveProgressFill").style.width = "0%";

    showPowerMessage(
        "SURVIVE!"
    );

}


document
    .getElementById(
        "startButton"
    )
    .addEventListener(
        "click",
        startGame
    );


/* =========================================================
   RESTART
========================================================= */

document
    .getElementById(
        "restartButton"
    )
    .addEventListener(
        "click",
        startGame
    );


/* =========================================================
   PAUSE
========================================================= */

function togglePause() {

    if (!gameRunning)
        return;


    paused =
        !paused;


    const pauseScreen =
        document.getElementById(
            "pauseScreen"
        );


    if (paused) {

        pauseScreen
            .classList
            .remove("hidden");
        updateHUD();

    }

    else {
        lastTime = performance.now();

        pauseScreen
            .classList
            .add("hidden");

    }

}


document
    .getElementById(
        "resumeButton"
    )
    .addEventListener(
        "click",
        togglePause
    );

document
    .getElementById(
        "pauseButton"
    )
    .addEventListener(
        "click",
        togglePause
    );

document
    .getElementById(
        "pauseRestartButton"
    )
    .addEventListener(
        "click",
        startGame
    );

document
    .getElementById("victoryRestartButton")
    .addEventListener("click", startGame);


/* =========================================================
   GAME OVER
========================================================= */

function endGame() {

    gameRunning = false;

    paused = false;


    const finalScore =
        Math.floor(score);


    document
        .getElementById(
            "finalScore"
        )
        .textContent =
        finalScore;


    /* Save score to PHP */

    fetch(
        window.location.href,
        {

            method: "POST",

            headers: {

                "Content-Type":
                    "application/json"

            },

            body:
                JSON.stringify({
                    score:
                        finalScore
                })

        }

    )
    .then(
        response =>
            response.json()
    )
    .then(
        data => {

            document
                .getElementById(
                    "highScore"
                )
                .textContent =
                data.highscore;

        }
    )
    .catch(
        () => {}
    );


    document
        .getElementById(
            "gameOver"
        )
        .classList
        .remove("hidden");

    document.getElementById("victoryScreen").classList.add("hidden");

}


/* =========================================================
   INITIAL POSITION
========================================================= */

player.x =
    width / 2;

player.y =
    height / 2;


/* =========================================================
   INITIAL HUD
========================================================= */

updateHUD();

</script>

</body>

</html>