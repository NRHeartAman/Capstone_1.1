<?php include 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CraveCast - Smart Dashboard</title>
    <link href="https://pro.fontawesome.com/releases/v6.0.0-beta3/css/all.css" rel="stylesheet">
    <link href="https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="Dstyle.css"> 
    <link rel="stylesheet" href="Fstyle.css">
    <style>
        /* White Theme Styling */
        body { background-color: #f8fafd; color: #333; }
        .card { background: #ffffff !important; color: #333 !important; border: 1px solid #e1e8ed; box-shadow: 0 4px 12px rgba(0,0,0,0.05); border-radius: 15px; }
        .weather-top-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
        h2, h1, p, span { color: #333 !important; }
        hr { border: 0; border-top: 1px solid #eee; }
        .hourly-forecast { display: flex; overflow-x: auto; gap: 15px; padding-bottom: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>CraveCast Weather & Prediction</h2>
            <div class="weather-input">
                <input type="text" id="city_input" placeholder="Enter City Name">
                <button type="button" id="searchBtn"><i class="fa-regular fa-search"></i> Search</button>
                <button type="button" id="locationBtn"><i class="bx bx-target-lock"></i></button>
            </div>
        </div>

        <div class="weather-top-grid">
            <div class="card" style="padding: 20px;">
                <div class="current weather" style="display: flex; justify-content: space-between;">
                    <div class="details">
                        <p>Current Weather</p>
                        <h2 id="currentTemp" style="font-size: 3.5rem; margin: 10px 0;">--&deg;C</h2>
                        <p id="currentCondition" style="font-weight: bold; color: #3C91E6 !important;">Fetching...</p>    
                    </div>
                    <div class="weather-icon">
                        <img id="mainIcon" src="" alt="" style="width: 100px;">
                    </div>
                </div>
                <hr>        
                <div class="card-footer" style="display: flex; justify-content: space-between; margin-top: 15px;">
                    <p id="currentDate"><i class="fa-light fa-calendar"></i> --</p>
                    <p id="currentLoc"><i class="fa-light fa-location-dot"></i> --</p>
                </div>
            </div>

            <div class="card" style="display: flex; flex-direction: column; justify-content: center; align-items: center; text-align: center; padding: 20px;">
                <p style="font-weight: bold; letter-spacing: 1px;">CRAVECAST AI PREDICTION</p>
                <?php
                    $output = shell_exec("py forecast.py 32");
                    $prediction = trim($output);
                ?>
                <h1 style="font-size: 5rem; color: #0c0c0c !important; margin: 5px 0;"><?php echo $prediction ?: '0'; ?></h1>
                <p style="font-weight: 500;">Cups of Milk Tea to prepare today</p>
                <small style="color: #888 !important;">Live XGBoost Analysis</small>
            </div>
        </div>

        <div class="weather-data">
            <div class="weather-left">
                <div class="card">
                    <h2 style="padding: 15px;">5 Days Forecast</h2>
                    <div class="day-forecast" id="dayForecast" style="padding: 0 15px 15px 15px;"></div>
                </div>
            </div>

            <div class="weather-right">
                <h2>Today's Highlights</h2>
                <div class="highlights">
                    <div class="card">
                        <div class="card-head"><p>Air Quality Index</p></div>
                        <div class="air-indices">
                            <i class="fa-regular fa-wind fa-3x" style="color: #3C91E6;"></i>
                            <div class="item"><p>PM2.5</p><h2 id="pm25">--</h2></div>
                            <div class="item"><p>PM10</p><h2 id="pm10">--</h2></div>
                            <div class="item"><p>SO2</p><h2 id="so2">--</h2></div>
                            <div class="item"><p>CO</p><h2 id="co">--</h2></div>
                            <div class="item"><p>O3</p><h2 id="o3">--</h2></div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header"><p>Sunrise & Sunset</p></div>
                        <div class="sunrise-sunset">
                            <div class="item"><i class="fa-light fa-sunrise fa-3x" style="color: #f39c12;"></i><div><p>Sunrise</p><h2 id="sunrise">--</h2></div></div>
                            <div class="item"><i class="fa-light fa-sunset fa-3x" style="color: #e67e22;"></i><div><p>Sunset</p><h2 id="sunset">--</h2></div></div>
                        </div>
                    </div>

                    <div class="card"><div class="card-head"><p>Humidity</p></div><h2 id="humidityVal">--%</h2></div>
                    <div class="card"><div class="card-head"><p>Feels Like</p></div><h2 id="feelsVal">--&deg;C</h2></div>
                    <div class="card"><div class="card-head"><p>Visibility</p></div><h2 id="visibilityVal">--km</h2></div>
                    <div class="card"><div class="card-head"><p>Wind Speed</p></div><h2 id="windSpeedVal">--m/s</h2></div>
                </div>

                <h2 style="margin-top: 30px;">Hourly Forecast</h2>
                <div class="hourly-forecast" id="hourlyForecast">
                    </div>  
            </div>  
        </div>
    </div>
    <script src="Fscript.js"></script>
</body>
</html>