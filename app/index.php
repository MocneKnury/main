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
    cursor: default;
    flex-shrink: 0;
    width: 320px;
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
    }
    .nav-tabs button {
      padding: 10px 16px;
      font-size: 1rem;
    }
    /* Na małym ekranie układ jednokolumnowy */
    .player-grid {
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
      overflow-x: visible;
      padding-bottom: 0;
    }
    .player-card {
      width: 100%;
      flex-shrink: 1;
    }
    .container {
      padding: 0 15px;
    }
  }
</style>
</head>
<body>
  <header>
    <h1>Mocne Knury Esport</h1>
  </header>

  <div class="nav-tabs">
    <button class="tab-button active" onclick="showTab('roster')">Skład</button>
    <button class="tab-button" onclick="showTab('matches')">Mecze</button>
  </div>

  <div class="container">
    <div id="roster" class="tab-content">
      <div class="team-title">Skład drużyny</div>
      <div class="player-grid">

        <div class="player-card">
          <h2>SimplyKubuS-</h2>
          <p>Wiek: 15 lat</p>
          <p><strong>Rola:</strong> 🎯 AWPer</p>
          <p><strong>Rating 2.0:</strong> 1.33</p>
          <p><strong>K/D:</strong> 1.34 | <strong>ADR:</strong> 93.4 | <strong>KPR:</strong> 0.91</p>
          <p><strong>DPR:</strong> 0.68 | <strong>KAST:</strong> 71.7%</p>
          <div class="skills">
            <div class="skill"><span>Firepower</span><div class="bar"><div class="fill" style="width: 90%;"></div></div></div>
            <div class="skill"><span>Entry</span><div class="bar"><div class="fill" style="width: 75%;"></div></div></div>
            <div class="skill"><span>Opening</span><div class="bar"><div class="fill" style="width: 80%;"></div></div></div>
            <div class="skill"><span>Sniping</span><div class="bar"><div class="fill" style="width: 85%;"></div></div></div>
            <div class="skill"><span>Clutching</span><div class="bar"><div class="fill" style="width: 70%;"></div></div></div>
            <div class="skill"><span>Utility</span><div class="bar"><div class="fill" style="width: 65%;"></div></div></div>
          </div>
        </div>

        <div class="player-card">
          <h2>Suv1337</h2>
          <p>Wiek: 15 lat</p>
          <p><strong>Rola:</strong> 🛡️ Riffler / Support</p>
          <p><strong>Rating 2.0:</strong> 1.23</p>
          <p><strong>K/D:</strong> 1.22 | <strong>ADR:</strong> 88.7 | <strong>KPR:</strong> 0.83</p>
          <p><strong>DPR:</strong> 0.68 | <strong>KAST:</strong> 69.5%</p>
          <div class="skills">
            <div class="skill"><span>Firepower</span><div class="bar"><div class="fill" style="width: 82%;"></div></div></div>
            <div class="skill"><span>Entry</span><div class="bar"><div class="fill" style="width: 70%;"></div></div></div>
            <div class="skill"><span>Opening</span><div class="bar"><div class="fill" style="width: 75%;"></div></div></div>
            <div class="skill"><span>Sniping</span><div class="bar"><div class="fill" style="width: 65%;"></div></div></div>
            <div class="skill"><span>Clutching</span><div class="bar"><div class="fill" style="width: 60%;"></div></div></div>
            <div class="skill"><span>Utility</span><div class="bar"><div class="fill" style="width: 60%;"></div></div></div>
          </div>
        </div>

        <div class="player-card">
          <h2>Wayferr</h2>
          <p>Wiek: 16 lat</p>
          <p><strong>Rola:</strong> 🧠 IGL</p>
          <p><strong>Rating 2.0:</strong> 0.99</p>
          <p><strong>K/D:</strong> 0.96 | <strong>ADR:</strong> 76.4 | <strong>KPR:</strong> 0.70</p>
          <p><strong>DPR:</strong> 0.75 | <strong>KAST:</strong> 62.0%</p>
          <div class="skills">
            <div class="skill"><span>Firepower</span><div class="bar"><div class="fill" style="width: 70%;"></div></div></div>
            <div class="skill"><span>Entry</span><div class="bar"><div class="fill" style="width: 60%;"></div></div></div>
            <div class="skill"><span>Opening</span><div class="bar"><div class="fill" style="width: 65%;"></div></div></div>
            <div class="skill"><span>Sniping</span><div class="bar"><div class="fill" style="width: 50%;"></div></div></div>
            <div class="skill"><span>Clutching</span><div class="bar"><div class="fill" style="width: 55%;"></div></div></div>
            <div class="skill"><span>Utility</span><div class="bar"><div class="fill" style="width: 50%;"></div></div></div>
          </div>
        </div>
<!-- wikokk1 -->
        <div class="player-card">
          <h2>wikokk1</h2>
          <p>Wiek: 16 lat</p>
          <p><strong>Rola:</strong> 🛡️ Riffler / Support</p>
          <p><strong>Rating 2.0:</strong> 0.50</p>
          <p><strong>K/D:</strong> 0.52 | <strong>ADR:</strong> 52.3 | <strong>KPR:</strong> 0.45</p>
          <p><strong>DPR:</strong> 0.86 | <strong>KAST:</strong> 44.9%</p>
          <div class="skills">
            <div class="skill"><span>Firepower</span><div class="bar"><div class="fill" style="width: 35%;"></div></div></div>
            <div class="skill"><span>Entry</span><div class="bar"><div class="fill" style="width: 40%;"></div></div></div>
            <div class="skill"><span>Opening</span><div class="bar"><div class="fill" style="width: 30%;"></div></div></div>
            <div class="skill"><span>Sniping</span><div class="bar"><div class="fill" style="width: 10%;"></div></div></div>
            <div class="skill"><span>Clutching</span><div class="bar"><div class="fill" style="width: 30%;"></div></div></div>
            <div class="skill"><span>Utility</span><div class="bar"><div class="fill" style="width: 50%;"></div></div></div>
          </div>
        </div>

        <!-- Divsero -->
        <div class="player-card">
          <h2>Divsero</h2>
          <p>Wiek: 16 lat</p>
          <p><strong>Rola:</strong> 🚪 Entry Fragger</p>
          <p><strong>Rating 2.0:</strong> 0.90</p>
          <p><strong>K/D:</strong> 0.91 | <strong>ADR:</strong> 72.0 | <strong>KPR:</strong> 0.67</p>
          <p><strong>DPR:</strong> 0.77 | <strong>KAST:</strong> 57.4%</p>
          <div class="skills">
            <div class="skill"><span>Firepower</span><div class="bar"><div class="fill" style="width: 75%;"></div></div></div>
            <div class="skill"><span>Entry</span><div class="bar"><div class="fill" style="width: 80%;"></div></div></div>
            <div class="skill"><span>Opening</span><div class="bar"><div class="fill" style="width: 70%;"></div></div></div>
            <div class="skill"><span>Sniping</span><div class="bar"><div class="fill" style="width: 15%;"></div></div></div>
            <div class="skill"><span>Clutching</span><div class="bar"><div class="fill" style="width: 45%;"></div></div></div>
            <div class="skill"><span>Utility</span><div class="bar"><div class="fill" style="width: 50%;"></div></div></div>
          </div>
        </div>
        <div class="player-card">
  <h2>Gawlaswp</h2>
  <p>Wiek: 15 lat</p>
  <p><strong>Rola:</strong> 🎓 Coach</p>
  <p><strong>Rating 2.0:</strong> -</p>
  <p><strong>K/D:</strong> - | <strong>ADR:</strong> - | <strong>KPR:</strong> -</p>
  <p><strong>DPR:</strong> - | <strong>KAST:</strong> -</p>
  <div class="skills">
    <div class="skill"><span>Strategia</span><div class="bar"><div class="fill" style="width: 85%;"></div></div></div>
    <div class="skill"><span>Motywacja</span><div class="bar"><div class="fill" style="width: 80%;"></div></div></div>
    <div class="skill"><span>Analiza</span><div class="bar"><div class="fill" style="width: 90%;"></div></div></div>
  </div>
</div>

      </div>
    </div>

    <div id="matches" class="tab-content hidden">
      <div class="team-title">Nadchodzące mecze</div>
      <p>Brak zaplanowanych meczów.</p>
    </div>
  </div>

  <script>
    function showTab(tabName) {
      const tabs = document.querySelectorAll('.tab-content');
      const buttons = document.querySelectorAll('.tab-button');
      tabs.forEach(tab => {
        tab.classList.toggle('hidden', tab.id !== tabName);
      });
      buttons.forEach(btn => {
        btn.classList.toggle('active', btn.textContent.toLowerCase() === tabName);
      });
    }
  </script>
</body>
</html>

