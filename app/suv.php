<?php
// Supabase configuration
$supabase_url = 'https://wfevbeddepuzwbrdhyen.supabase.co/rest/v1/players';
$supabase_key = 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJpc3MiOiJzdXBhYmFzZSIsInJlZiI6IndmZXZiZWRkZXB1endicmRoeWVuIiwicm9sZSI6ImFub24iLCJpYXQiOjE3NDk1MDUzNTMsImV4cCI6MjA2NTA4MTM1M30.feV4vn3-XVgQwLtJy_EDJR_rQyy1Ny3pue6SGN_Ls10';

// Function to call Supabase REST API with cURL
function supabase_request($method, $url, $key, $body = null) {
    $curl = curl_init();

    $headers = [
        "apikey: $key",
        "Authorization: Bearer $key",
        "Content-Type: application/json",
        "Accept: application/json",
        "Prefer: return=representation" // To get updated record in response
    ];

    curl_setopt_array($curl, [
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_FOLLOWLOCATION => true,
    ]);

    if ($body !== null) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($body));
    }

    $response = curl_exec($curl);

    if (curl_errno($curl)) {
        curl_close($curl);
        return false;
    }

    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    return ['status' => $http_code, 'body' => json_decode($response, true)];
}

// Fetch player Suv1337 data
$filter = urlencode('nickname=eq.Suv1337');
$fetch_url = $supabase_url . "?$filter&select=*";

$fetch_result = supabase_request('GET', $fetch_url, $supabase_key);

if (!$fetch_result || $fetch_result['status'] !== 200 || empty($fetch_result['body'])) {
    exit('Player Suv1337 not found in database or error fetching data.');
}

$player = $fetch_result['body'][0];

// Initialize messages
$success = $error = '';

// Process POST update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and validate input
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
    $role = trim($_POST['role'] ?? '');
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_FLOAT);
    $faceit_level = filter_input(INPUT_POST, 'faceit_level', FILTER_VALIDATE_INT);
    $kd_ratio = filter_input(INPUT_POST, 'kd_ratio', FILTER_VALIDATE_FLOAT);
    $adr = filter_input(INPUT_POST, 'adr', FILTER_VALIDATE_FLOAT);
    $kpr = filter_input(INPUT_POST, 'kpr', FILTER_VALIDATE_FLOAT);
    $dpr = filter_input(INPUT_POST, 'dpr', FILTER_VALIDATE_FLOAT);
    $kast = filter_input(INPUT_POST, 'kast', FILTER_VALIDATE_FLOAT);
    $favorite_map = trim($_POST['favorite_map'] ?? '');
    $favorite_weapon = trim($_POST['favorite_weapon'] ?? '');

    $skills = [
        'Firepower' => filter_input(INPUT_POST, 'skill_firepower', FILTER_VALIDATE_INT),
        'Entry' => filter_input(INPUT_POST, 'skill_entry', FILTER_VALIDATE_INT),
        'Opening' => filter_input(INPUT_POST, 'skill_opening', FILTER_VALIDATE_INT),
        'Sniping' => filter_input(INPUT_POST, 'skill_sniping', FILTER_VALIDATE_INT),
        'Clutching' => filter_input(INPUT_POST, 'skill_clutching', FILTER_VALIDATE_INT),
        'Utility' => filter_input(INPUT_POST, 'skill_utility', FILTER_VALIDATE_INT),
    ];

    // Validation
    if ($age === false || $age < 12 || $age > 100) {
        $error = 'Please enter a valid age (12-100).';
    } elseif ($faceit_level === false || $faceit_level < 0 || $faceit_level > 10) {
        $error = 'Please enter a valid Faceit level (0-10).';
    } else {
        // Prepare updated data
        $update_data = [
            'age' => $age,
            'role' => $role,
            'rating' => $rating,
            'faceit_level' => $faceit_level,
            'kd_ratio' => $kd_ratio,
            'adr' => $adr,
            'kpr' => $kpr,
            'dpr' => $dpr,
            'kast' => $kast,
            'favorite_map' => $favorite_map,
            'favorite_weapon' => $favorite_weapon,
            'skills_json' => json_encode($skills)
        ];

        // Supabase PATCH URL with filter for nickname
        $patch_url = $supabase_url . "?nickname=eq.Suv1337";

        $patch_result = supabase_request('PATCH', $patch_url, $supabase_key, $update_data);

        if ($patch_result && in_array($patch_result['status'], [200, 204])) {
            $success = 'Data successfully updated.';
            // Refresh player data with returned updated record if any
            if (!empty($patch_result['body'])) {
                $player = $patch_result['body'][0];
            } else {
                // fallback: refetch
                $fetch_result = supabase_request('GET', $fetch_url, $supabase_key);
                if ($fetch_result && $fetch_result['status'] === 200 && !empty($fetch_result['body'])) {
                    $player = $fetch_result['body'][0];
                }
            }
        } else {
            $error = 'Error occurred updating data.';
        }
    }
}


?>
<!DOCTYPE html>
<html lang="pl">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Edytuj dane Suv1337 - Mocne Knury Esport</title>
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
    align-items: center;
    padding: 30px 20px;
  }
  main {
    background: #1f1f1f;
    border-radius: 15px;
    padding: 30px 35px 40px;
    box-shadow: 0 6px 18px rgba(0, 0, 0, 0.6);
    max-width: 600px;
    width: 100%;
  }
  h1 {
    font-size: 2.8rem;
    color: #22c55e;
    margin: 0 0 20px 0;
    text-shadow: 0 0 8px #22c55e;
    letter-spacing: 2px;
    font-weight: 700;
  }
  form label {
    display: block;
    font-weight: 600;
    color: #86efac;
    margin-top: 22px;
    margin-bottom: 8px;
    user-select: none;
  }
  input[type="text"],
  input[type="number"] {
    width: 100%;
    background: #121212;
    border: 1.5px solid #22c55e;
    border-radius: 8px;
    color: #e5e5e5;
    padding: 10px 14px;
    font-size: 1rem;
    outline: none;
    transition: border-color 0.3s ease;
    font-weight: 600;
  }
  input[type="text"]:focus,
  input[type="number"]:focus {
    border-color: #86efac;
    box-shadow: 0 0 8px #22c55eaa;
  }
  /* Slider styling */
  .slider-container {
    position: relative;
    margin-top: 8px;
  }
  input[type="range"] {
    -webkit-appearance: none;
    width: 100%;
    height: 12px;
    border-radius: 8px;
    background: #2e2e2e;
    outline: none;
    box-shadow: inset 0 1px 2px rgba(0,0,0,0.7);
    cursor: pointer;
  }
  input[type="range"]::-webkit-slider-thumb {
    -webkit-appearance: none;
    appearance: none;
    width: 24px;
    height: 24px;
    background: #22c55e;
    cursor: pointer;
    border-radius: 50%;
    box-shadow: 0 0 8px #22c55eaa;
    border: 2px solid #16a34a;
    margin-top: -6px;
    transition: background-color 0.3s ease;
  }
  input[type="range"]:focus::-webkit-slider-thumb {
    background: #86efac;
  }
  input[type="range"]::-moz-range-thumb {
    width: 24px;
    height: 24px;
    background: #22c55e;
    cursor: pointer;
    border-radius: 50%;
    border: 2px solid #16a34a;
    box-shadow: 0 0 8px #22c55eaa;
    transition: background-color 0.3s ease;
  }
  input[type="range"]:focus::-moz-range-thumb {
    background: #86efac;
  }
  /* Slider fill track */
  .slider-fill {
    position: absolute;
    height: 12px;
    border-radius: 8px;
    top: 0;
    left: 0;
    background: #22c55e;
    pointer-events: none;
    z-index: 1;
    transition: width 0.3s ease;
  }
  button[type="submit"] {
    margin-top: 30px;
    background: transparent;
    border: 2.5px solid #22c55e;
    color: #22c55e;
    padding: 12px 30px;
    font-size: 1.25rem;
    font-weight: 700;
    border-radius: 30px;
    cursor: pointer;
    box-shadow: 0 0 8px #22c55e;
    transition: all 0.3s ease;
    width: 100%;
    user-select: none;
    white-space: nowrap;
  }
  button[type="submit"]:hover,
  button[type="submit"]:focus {
    background: #22c55e;
    color: #111;
    box-shadow: 0 0 20px #22c55e;
    outline: none;
  }
  .message {
    margin-top: 20px;
    font-weight: 700;
    font-size: 1rem;
    border-radius: 12px;
    padding: 12px 18px;
    user-select: none;
  }
  .message.success {
    background-color: #164e10;
    color: #a7f3d0;
    box-shadow: 0 0 15px #22c55eaa;
  }
  .message.error {
    background-color: #601a1a;
    color: #fecaca;
  }
</style>
</head>
<body>
  <main>
    <h1>Edytuj dane <span>Suv1337</span></h1>
    <?php if ($success): ?>
      <div class="message success"><?=htmlspecialchars($success)?></div>
    <?php elseif ($error): ?>
      <div class="message error"><?=htmlspecialchars($error)?></div>
    <?php endif; ?>
    <form method="POST" novalidate>
      <label for="age">Wiek</label>
      <input type="number" id="age" name="age" min="12" max="100" required value="<?=htmlspecialchars($player['age'])?>" />

      <label for="role">Rola</label>
      <input type="text" id="role" name="role" required value="<?=htmlspecialchars($player['role'])?>" />

      <label for="rating">Rating 2.0</label>
      <input type="number" step="0.01" id="rating" name="rating" value="<?=htmlspecialchars($player['rating'])?>" />

      <label for="faceit_level">Faceit lvl</label>
      <input type="number" id="faceit_level" name="faceit_level" min="0" max="10" required value="<?=htmlspecialchars($player['faceit_level'])?>" />

      <label for="kd_ratio">K/D</label>
      <input type="number" step="0.01" id="kd_ratio" name="kd_ratio" value="<?=htmlspecialchars($player['kd_ratio'])?>" />

      <label for="adr">ADR</label>
      <input type="number" step="0.1" id="adr" name="adr" value="<?=htmlspecialchars($player['adr'])?>" />

      <label for="kpr">KPR</label>
      <input type="number" step="0.01" id="kpr" name="kpr" value="<?=htmlspecialchars($player['kpr'])?>" />

      <label for="dpr">DPR</label>
      <input type="number" step="0.01" id="dpr" name="dpr" value="<?=htmlspecialchars($player['dpr'])?>" />

      <label for="kast">KAST (%)</label>
      <input type="number" step="0.01" id="kast" name="kast" value="<?=htmlspecialchars($player['kast'])?>" />

      <label for="favorite_map">Ulubiona mapa</label>
      <input type="text" id="favorite_map" name="favorite_map" value="<?=htmlspecialchars($player['favorite_map'])?>" />

      <label for="favorite_weapon">Ulubiona broń</label>
      <input type="text" id="favorite_weapon" name="favorite_weapon" value="<?=htmlspecialchars($player['favorite_weapon'])?>" />

      <label for="skill_firepower">Firepower</label>
      <div class="slider-container">
        <input type="range" id="skill_firepower" name="skill_firepower" min="0" max="100" value="<?=htmlspecialchars($skills['Firepower'])?>" />
        <div class="slider-fill" id="fill_firepower"></div>
      </div>

      <label for="skill_entry">Entry</label>
      <div class="slider-container">
        <input type="range" id="skill_entry" name="skill_entry" min="0" max="100" value="<?=htmlspecialchars($skills['Entry'])?>" />
        <div class="slider-fill" id="fill_entry"></div>
      </div>

      <label for="skill_opening">Opening</label>
      <div class="slider-container">
        <input type="range" id="skill_opening" name="skill_opening" min="0" max="100" value="<?=htmlspecialchars($skills['Opening'])?>" />
        <div class="slider-fill" id="fill_opening"></div>
      </div>

      <label for="skill_sniping">Sniping</label>
      <div class="slider-container">
        <input type="range" id="skill_sniping" name="skill_sniping" min="0" max="100" value="<?=htmlspecialchars($skills['Sniping'])?>" />
        <div class="slider-fill" id="fill_sniping"></div>
      </div>

      <label for="skill_clutching">Clutching</label>
      <div class="slider-container">
        <input type="range" id="skill_clutching" name="skill_clutching" min="0" max="100" value="<?=htmlspecialchars($skills['Clutching'])?>" />
        <div class="slider-fill" id="fill_clutching"></div>
      </div>

      <label for="skill_utility">Utility</label>
      <div class="slider-container">
        <input type="range" id="skill_utility" name="skill_utility" min="0" max="100" value="<?=htmlspecialchars($skills['Utility'])?>" />
        <div class="slider-fill" id="fill_utility"></div>
      </div>

      <button type="submit">Zapisz zmiany</button>
    </form>
  </main>
<script>
  // Update slider fill widths dynamically
  function updateFill(slider, fill) {
    const val = slider.value;
    fill.style.width = val + '%';
  }

  document.querySelectorAll('input[type="range"]').forEach(slider => {
    const fill = document.getElementById('fill_' + slider.id.replace('skill_', ''));
    if (fill) {
      updateFill(slider, fill);
      slider.addEventListener('input', () => updateFill(slider, fill));
    }
  });
</script>
</body>
</html>
