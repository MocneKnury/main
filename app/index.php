<?php
// DB connection parameters - set according to environment
$host = 'localhost';
$db   = 'knury'; // assuming db name is knury
$user = 'root';
$pass = ''; // change as needed
$charset = 'utf8mb4';

// DSN and options for PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Database connection failed.');
}

// Fetch roster players
$stmtRoster = $pdo->query("SELECT * FROM players WHERE team_role = 'roster' ORDER BY id ASC");
$rosterPlayers = $stmtRoster->fetchAll();

// Fetch bench players
$stmtBench = $pdo->query("SELECT * FROM players WHERE team_role = 'bench' ORDER BY id ASC");
$benchPlayers = $stmtBench->fetchAll();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Mocne Knury Esport</title>
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet" />
<style>
  /* Reset i podstawy */
  * {
    box-sizing: border-box;
  }
  body {
    font-family: 'Roboto', sans-serif;
    background: linear-gradient(135deg, #0a0a0a 0%, #121212 100%);
    color: #e5e5e5;
    margin: 0;
    min-height: 100vh;
    display: flex;
    flex-direction: column;
  }
  /* HEADER */
  header {
    background-color: #111;
    padding: 25px 50px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 4px solid #22c55e;
    box-shadow: 0 3px 8px rgba(34, 197, 94, 0.4);
    position: sticky;
    top: 0;
    z-index: 1000;
  }
  header h1 {
    font-size: 2.8rem;
    color: #22c55e;
    margin: 0;
    text-shadow: 0 0 8px #22c55e;
    letter-spacing: 2px;
  }
  /* Logowanie button */
  .login-button {
    background: transparent;
    border: 2.5px solid #22c55e;
    color: #22c55e;
    padding: 10px 26px;
    font-size: 1.1rem;
    font-weight: 600;
    border-radius: 30px;
    cursor: pointer;
    text-decoration: none;
    box-shadow: 0 0 8px #22c55e;
    transition: all 0.3s ease;
    user-select: none;
    white-space: nowrap;
  }
  .login-button:hover,
  .login-button:focus {
    background: #22c55e;
    color: #111;
    box-shadow: 0 0 20px #22c55e;
    outline: none;
  }
  /* NAV TABS */
  .nav-tabs {
    display: flex;
    justify-content: center;
    margin: 30px 0 40px 0;
    gap: 25px;
    user-select: none;
  }
  .nav-tabs button {
    background: transparent;
    border: 2.5px solid transparent;
    padding: 12px 28px;
    font-size: 1.15rem;
    color: #a5a5a5;
    cursor: pointer;
    border-radius: 30px;
    transition: all 0.3s ease;
    box-shadow: 0 0 0 rgba(34, 197, 94, 0);
    font-weight: 600;
  }
  .nav-tabs button:hover:not(.active) {
    color: #22c55e;
    border-color: #22c55e;
    box-shadow: 0 0 8px #22c55e;
  }
  .nav-tabs button.active {
    border-color: #22c55e;
    color: #22c55e;
    font-weight: 700;
    box-shadow: 0 0 15px #22c55e;
    background: rgba(34, 197, 94, 0.12);
  }
  /* CONTAINER */
  .container {
    max-width: 1200px;
    margin: 0 auto 60px;
    padding: 0 30px;
    flex-grow: 1;
  }
  /* TEAM TITLE */
  .team-title {
    font-size: 2rem;
    margin-bottom: 30px;
    color: #93c5fd;
    border-bottom: 3px solid #22c55e;
    padding-bottom: 12px;
    letter-spacing: 1px;
    text-shadow: 0 0 5px #22c55e;
    font-weight: 700;
  }
  /* PLAYER GRID - flex z zawijaniem */
  .player-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 28px;
    justify-content: flex-start;
    overflow: visible;
  }
  /* PLAYER CARD */
  .player-card {
    background: #1f1f1f;
    border-radius: 15px;
    padding: 28px 30px 35px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.6);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    cursor: pointer;
    flex-shrink: 0;
    width: 320px;
    position: relative;
  }
  .player-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 15px 35px rgba(34, 197, 94, 0.5);
  }
  .player-card h2 {
    margin: 0 0 12px;
    font-size: 1.6rem;
    color: #22c55e;
    letter-spacing: 0.8px;
    text-shadow: 0 0 7px #22c55e;
  }
  .player-card p {
    margin: 6px 0;
    font-size: 1rem;
    line-height: 1.3;
    color: #cbd5e1;
  }
  .player-card p strong {
    color: #86efac;
  }
  /* FACEIT LEVEL */
  .faceit-level {
    margin-top: 6px;
    font-weight: 600;
    font-size: 1rem;
    color: #22c55e;
    text-shadow: 0 0 6px #22c55e;
    user-select: none;
  }
  /* SKILLS */
  .skills {
    margin-top: 18px;
  }
  .skill {
    margin-bottom: 10px;
    font-size: 0.9rem;
    color: #a5b4fc;
    display: flex;
    flex-direction: column;
    user-select: none;
  }
  .skill span {
    margin-bottom: 5px;
    font-weight: 600;
    letter-spacing: 0.5px;
    color: #60a5fa;
    text-shadow: 0 0 4px #22c55e;
  }
  .bar {
    background-color: #2e2e2e;
    border-radius: 8px;
    overflow: hidden;
    height: 12px;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.7);
  }
  .fill {
    background-color: #22c55e;
    height: 100%;
    border-radius: 8px 0 0 8px;
    transition: width 0.5s ease;
    box-shadow: 0 0 8px #22c55e;
  }
  /* Scale line for skill bars */
  .scale-line {
    font-size: 0.85rem;
    color: #4ade80;
    margin-top: 4px;
    display: flex;
    justify-content: space-between;
    user-select: none;
    font-weight: 600;
    letter-spacing: 0.8px;
    text-shadow: 0 0 3px #22c55e;
  }

  /* BENCH PLAYER CARD */
  .bench-player-card {
    background: #1f1f1f;
    border-radius: 15px;
    padding: 20px 24px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.5);
    width: 320px;
    color: #cbd5e1;
    display: flex;
    flex-direction: column;
    gap: 8px;
  }
  .bench-player-card label {
    font-weight: 600;
    color: #22c55e;
    user-select: none;
  }
  .bench-player-card input[type="text"] {
    background: #121212;
    border: 1.5px solid #22c55e;
    border-radius: 8px;
    color: #e5e5e5;
    padding: 8px 12px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s ease;
  }
  .bench-player-card input[type="text"]:focus {
    border-color: #86efac;
    box-shadow: 0 0 8px #22c55eaa;
  }
  /* TAB CONTENT */
  .tab-content.hidden {
    display: none;
  }
  /* MATCHES SECTION */
  #matches p {
    font-size: 1.1rem;
    color: #94a3b8;
    text-align: center;
    margin-top: 30px;
    font-style: italic;
  }
  /* RESPONSYWNOŚĆ */
  @media (max-width: 480px) {
    header h1 {
      font-size: 1.8rem;
    }
    .nav-tabs {
      gap: 15px;
      flex-wrap: wrap;
      justify-content: center;
    }
    .nav-tabs button {
      padding: 10px 16px;
      font-size: 1rem;
      flex-grow: 1;
      max-width: 180px;
    }
    /* Na małym ekranie układ jednokolumnowy */
    .player-grid, #bench .bench-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
      overflow-x: visible;
      padding-bottom: 0;
    }
    .player-card, .bench-player-card {
      width: 100%;
      flex-shrink: 1;
    }
    .container {
      padding: 0 15px;
    }
  }

  /* NOWY STYL DLA DETALI */
  .player-details {
    background: #272727;
    margin-top: 20px;
    padding: 15px 20px;
    border-radius: 12px;
    box-shadow: 0 0 15px #22c55eaa;
    color: #a5f3a5;
    font-size: 1rem;
    user-select: none;
  }
  .player-details strong {
    color: #22c55e;
  }

  /* FOOTER */
  footer {
    background-color: #121212;
    border-top: 4px solid #22c55e;
    margin-top: auto;
    box-shadow: 0 -3px 8px rgba(34, 197, 94, 0.4);
  }
  .footer-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 20px 30px;
    display: flex;
    flex-direction: column;
    align-items: center;
  }
  .discord-button {
    background: #5865f2;
    border: none;
    border-radius: 8px;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    color: white;
    font-weight: 600;
    font-size: 1rem;
    cursor: pointer;
    user-select: none;
    box-shadow: 0 0 10px #5865f2aa;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    text-decoration: none;
    justify-content: center;
    width: fit-content;
  }
  .discord-button:hover,
  .discord-button:focus {
    background: #4752c4;
    box-shadow: 0 0 18px #4752c4cc;
    outline: none;
  }
  .discord-button svg {
    width: 22px;
    height: 22px;
    margin-right: 8px;
    fill: white;
  }
  .footer-copyright {
    margin-top: 12px;
    font-size: 0.9rem;
    color: #94a3b8;
    user-select: none;
    font-weight: 600;
    letter-spacing: 0.6px;
  }
</style>
</head>
<body>
  <header>
    <h1>Mocne Knury Esport</h1>
    <a href="logowanie.php" class="login-button" aria-label="Przejdź do logowania">Logowanie</a>
  </header>

  <div class="nav-tabs" role="tablist" aria-label="Nawigacja zespołu">
    <button class="tab-button active" role="tab" aria-selected="true" aria-controls="roster" id="tab-roster" onclick="showTab('roster')">Skład</button>
    <button class="tab-button" role="tab" aria-selected="false" aria-controls="bench" id="tab-bench" onclick="showTab('bench')">Ławka</button>
    <button class="tab-button" role="tab" aria-selected="false" aria-controls="matches" id="tab-matches" onclick="showTab('matches')">Mecze</button>
  </div>

  <div class="container">
    <div id="roster" class="tab-content">
      <div class="team-title">Skład drużyny</div>
      <div class="player-grid">
        <?php foreach ($rosterPlayers as $player): ?>
          <div class="player-card" data-map="<?=htmlspecialchars($player['favorite_map'])?>" data-weapon="<?=htmlspecialchars($player['favorite_weapon'])?>" onclick="toggleDetails(this)" tabindex="0">
            <h2><?=htmlspecialchars($player['nickname'])?></h2>
            <p>Wiek: <?=htmlspecialchars($player['age'])?> lat</p>
            <p><strong>Rola:</strong>
              <?php
                $role = htmlspecialchars($player['role']);
                $emoji = '';
                if (stripos($role, 'awper') !== false) $emoji = '🎯';
                else if (stripos($role, 'riffler') !== false) $emoji = '🛡️';
                else if (stripos($role, 'igl') !== false) $emoji = '🧠';
                else if (stripos($role, 'entry') !== false) $emoji = '🚪';
                else if (stripos($role, 'coach') !== false) $emoji = '🎓';
                else $emoji = '🎮';
                echo $emoji . ' ' . $role;
              ?>
            </p>
            <p><strong>Rating 2.0:</strong> <?=htmlspecialchars($player['rating'] ?? '-')?></p>
            <div class="faceit-level">
              <?php
                $lvlIcon = '';
                $lvl = (int)($player['faceit_level'] ?? 0);
                if ($lvl >= 7) $lvlIcon = '💎';
                else if ($lvl >= 4) $lvlIcon = '⭐';
                else if ($lvl > 0) $lvlIcon = '🔷';
                else $lvlIcon = '';
                if ($lvlIcon !== '') {
                    echo $lvlIcon . ' lvl' . $lvl;
                } else {
                    echo '-';
                }
              ?>
            </div>
            <p>
              <strong>K/D:</strong> <?=htmlspecialchars($player['kd_ratio'] ?? '-')?> |
              <strong>ADR:</strong> <?=htmlspecialchars($player['adr'] ?? '-')?> |
              <strong>KPR:</strong> <?=htmlspecialchars($player['kpr'] ?? '-')?>
            </p>
            <p>
              <strong>DPR:</strong> <?=htmlspecialchars($player['dpr'] ?? '-')?> |
              <strong>KAST:</strong> <?=htmlspecialchars($player['kast'] ?? '-')?>%
            </p>
            <div class="skills">
              <?php
                $skills = json_decode($player['skills_json'] ?? '{}', true);
                foreach ($skills as $skillName => $skillValue):
              ?>
                <div class="skill">
                  <span><?=htmlspecialchars($skillName)?></span>
                  <div class="bar"><div class="fill" style="width: <?=intval($skillValue)?>%;"></div></div>
                  <div class="scale-line"><div>0</div><div>100</div></div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="player-details" style="display:none;"></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div id="bench" class="tab-content hidden">
      <div class="team-title">Ławka rezerwowych</div>
      <div class="bench-grid">
        <?php foreach ($benchPlayers as $player): ?>
          <div class="player-card" data-map="<?=htmlspecialchars($player['favorite_map'])?>" data-weapon="<?=htmlspecialchars($player['favorite_weapon'])?>" onclick="toggleDetails(this)" tabindex="0">
            <h2><?=htmlspecialchars($player['nickname'])?></h2>
            <p>Wiek: <?=htmlspecialchars($player['age'])?> lat</p>
            <p><strong>Rola:</strong> <?=htmlspecialchars($player['role'])?></p>
            <p><strong>Rating 2.0:</strong> <?=htmlspecialchars($player['rating'] ?? '-')?></p>
            <div class="faceit-level">
              <?php
                $lvlIcon = '';
                $lvl = (int)($player['faceit_level'] ?? 0);
                if ($lvl >= 7) $lvlIcon = '💎';
                else if ($lvl >= 4) $lvlIcon = '⭐';
                else if ($lvl > 0) $lvlIcon = '🔷';
                else $lvlIcon = '';
                if ($lvlIcon !== '') {
                    echo $lvlIcon . ' lvl' . $lvl;
                } else {
                    echo '-';
                }
              ?>
            </div>
            <p>
              <strong>K/D:</strong> <?=htmlspecialchars($player['kd_ratio'] ?? '-')?> |
              <strong>ADR:</strong> <?=htmlspecialchars($player['adr'] ?? '-')?> |
              <strong>KPR:</strong> <?=htmlspecialchars($player['kpr'] ?? '-')?>
            </p>
            <p>
              <strong>DPR:</strong> <?=htmlspecialchars($player['dpr'] ?? '-')?> |
              <strong>KAST:</strong> <?=htmlspecialchars($player['kast'] ?? '-')?>%
            </p>
            <div class="skills">
              <?php
                $skills = json_decode($player['skills_json'] ?? '{}', true);
                foreach ($skills as $skillName => $skillValue):
              ?>
                <div class="skill">
                  <span><?=htmlspecialchars($skillName)?></span>
                  <div class="bar"><div class="fill" style="width: <?=intval($skillValue)?>%;"></div></div>
                  <div class="scale-line"><div>0</div><div>100</div></div>
                </div>
              <?php endforeach; ?>
            </div>
            <div class="player-details" style="display:none;"></div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div id="matches" class="tab-content hidden">
      <div class="team-title">Nadchodzące mecze</div>
      <p>Brak danych o nadchodzących meczach.</p>
    </div>
  </div>

  <footer>
    <div class="footer-container">
      <a href="https://discord.com/invite/jtn5tqFCjD" target="_blank" rel="noopener noreferrer" class="discord-button" aria-label="Dołącz do Discorda Mocne Knury Esport">
        <svg aria-hidden="true" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M20.317 4.3698a19.791 19.791 0 0 0-4.8851-1.5152.0741.0741 0 0 0-.0785.0371c-.211.3753-.4447.8648-.6083 1.2495-1.8447-.2762-3.68-.2762-5.4868 0-.1636-.3923-.4058-.8742-.6177-1.2495a.077.077 0 0 0-.0785-.037 19.736 19.736 0 0 0-4.8852 1.515.0699.0699 0 0 0-.0321.0277C2.13 9.0458 1.367 13.579 1.8384 18.057a.0824.0824 0 0 0 .0312.0561 19.9 19.9 0 0 0 5.993 3.04.0777.0777 0 0 0 .0842-.0276c.4626-.63.8731-1.2952 1.226-1.9942a.076.076 0 0 0-.0416-.1057 13.14 13.14 0 0 1-1.872-.888.077.077 0 0 1-.0076-.1277c.1258-.0943.2517-.1923.3718-.2914a.0743.0743 0 0 1 .0776-.01c3.927 1.793 8.18 1.793 12.061 0a.0739.0739 0 0 1 .0785.0099c.12.099.246.198.372.292a.0766.0766 0 0 1-.006.1276 12.55 12.55 0 0 1-1.873.888.0766.0766 0 0 0-.0407.106c.3604.698.771 1.363 1.233 1.9938a.076.076 0 0 0 .0842.028 19.876 19.876 0 0 0 6.002-3.043.082.082 0 0 0 .03-.055c.5-5.177-.838-9.673-3.548-13.66a.061.061 0 0 0-.0312-.0286Z" fill="currentColor"/>
          <path d="M8.02 15.3312c-1.1825 0-2.1569-1.0857-2.1569-2.419 0-1.3332.9555-2.4189 2.157-2.4189 1.2108 0 2.1852 1.0952 2.1568 2.4189 0 1.3333-.9555 2.419-2.1569 2.419Zm7.9748 0c-1.1824 0-2.1568-1.0857-2.1568-2.419 0-1.3332.9554-2.4189 2.1568-2.4189 1.2109 0 2.1853 1.0952 2.157 2.4189 0 1.3333-.9461 2.419-2.157 2.419Z" fill="white"/>
        </svg>
        Discord
      </a>
      <div class="footer-copyright">Copyright 2025 MocneKnury.</div>
    </div>
  </footer>

<script>
  // Przełączanie tabów
  function showTab(tabId) {
    document.querySelectorAll('.tab-content').forEach(tab => {
      tab.classList.toggle('hidden', tab.id !== tabId);
    });
    document.querySelectorAll('.nav-tabs button').forEach(btn => {
      btn.classList.toggle('active', btn.textContent.toLowerCase() === tabId);
    });
  }

  // Pokazuje/ukrywa szczegóły ulubionej mapy i broni
  function toggleDetails(card) {
    const detailsDiv = card.querySelector('.player-details');
    if (!detailsDiv) return;

    if (detailsDiv.style.display === 'block') {
      // schowaj, jeśli już widoczne
      detailsDiv.style.display = 'none';
      return;
    }

    // wypełnij danymi z atrybutów data-*
    const favoriteMap = card.getAttribute('data-map');
    const favoriteWeapon = card.getAttribute('data-weapon');

    detailsDiv.innerHTML = `
      <strong>Ulubiona mapa:</strong> ${favoriteMap}<br />
      <strong>Ulubiona broń:</strong> ${favoriteWeapon}
    `;
    detailsDiv.style.display = 'block';
  }
</script>
</body>
</html>

