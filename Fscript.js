const api_key = '6f91eab4bee94998814103029260904';

async function getXGBoostPrediction(temp) {
    const predictionH1 = document.getElementById('predictionValue'); 
    const aiStatus = document.getElementById('aiStatus');
    const predictionCard = document.querySelector('.ai-prediction'); // I-target ang container

    if (predictionH1) predictionH1.innerText = "...";
    
    try {
        const response = await fetch(`get_prediction.php?temp=${temp}`);
        const result = await response.text();
        const count = parseInt(result);
        
        if (predictionH1) {
            predictionH1.innerText = result;

            // COLOR LOGIC: Magbabago ang kulay base sa dami ng cups
            if (count >= 100) {
                predictionH1.style.color = "#e74c3c"; // Red for High Demand
                if(aiStatus) aiStatus.innerHTML = "● Peak Demand <i class='bx bxs-hot'></i>";
                if(aiStatus) aiStatus.style.color = "#e74c3c";
            } else if (count >= 50) {
                predictionH1.style.color = "#3C91E6"; // Blue for Normal/Stable
                if(aiStatus) aiStatus.innerHTML = "● Optimal Demand <i class='bx bxs-check-circle'></i>";
                if(aiStatus) aiStatus.style.color = "#3C91E6";
            } else {
                predictionH1.style.color = "#95a5a6"; // Gray for Low Demand
                if(aiStatus) aiStatus.innerHTML = "● Low Demand <i class='bx bxs-info-circle'></i>";
                if(aiStatus) aiStatus.style.color = "#95a5a6";
            }
        }
    } catch (error) {
        console.error("AI Prediction Error:", error);
        if (predictionH1) predictionH1.innerText = "Error";
    }
}

// 2. MAIN WEATHER FUNCTION
async function getWeatherDetails(city) {
    const API_URL = `https://api.weatherapi.com/v1/forecast.json?key=${api_key}&q=${city}&days=5&aqi=yes`;

    try {
        const response = await fetch(API_URL);
        const data = await response.json();

        // --- A. SYNC SA USER PAGE (user_page.php) ---
        const userStatus = document.getElementById('user-weather-status');
        const userTemp = document.getElementById('user-temp-display');
        const userCityName = document.getElementById('user-city-name');

        if (userStatus) userStatus.innerHTML = `<img src="https:${data.current.condition.icon}" width="25"> ${data.current.condition.text}`;
        if (userTemp) userTemp.innerText = `${data.current.temp_c}°C`;
        if (userCityName) userCityName.innerText = data.location.name;

        // --- B. DASHBOARD MAIN ELEMENTS (dashboard.php) ---
        const currentTemp = document.getElementById('currentTemp');
        if (currentTemp) {
            currentTemp.innerHTML = `${data.current.temp_c}&deg;C`;
            document.getElementById('currentCondition').innerText = data.current.condition.text;
            document.getElementById('mainIcon').src = "https:" + data.current.condition.icon;
            document.getElementById('currentDate').innerText = new Date().toDateString();
            document.getElementById('currentLoc').innerText = `${data.location.name}, ${data.location.region}`;
        }

        // --- C. AI PREDICTION CALL ---
        getXGBoostPrediction(data.current.temp_c);

        // --- D. HIGHLIGHTS (Safety Check bago i-update) ---
        if (document.getElementById('humidityVal')) {
            document.getElementById('humidityVal').innerText = `${data.current.humidity}%`;
            document.getElementById('feelsVal').innerHTML = `${data.current.feelslike_c}&deg;C`;
            document.getElementById('visibilityVal').innerText = `${data.current.vis_km}km`;
            document.getElementById('windSpeedVal').innerText = `${(data.current.wind_kph / 3.6).toFixed(1)} m/s`;
            
            const aqi = data.current.air_quality;
            document.getElementById('pm25').innerText = aqi.pm2_5.toFixed(1);
            document.getElementById('pm10').innerText = aqi.pm10.toFixed(1);
            document.getElementById('so2').innerText = aqi.so2.toFixed(1);
            document.getElementById('co').innerText = Math.round(aqi.co);
            document.getElementById('o3').innerText = aqi.o3.toFixed(1);

            document.getElementById('sunrise').innerText = data.forecast.forecastday[0].astro.sunrise;
            document.getElementById('sunset').innerText = data.forecast.forecastday[0].astro.sunset;
        }

        // --- E. 5 DAY FORECAST ---
        const forecastDiv = document.getElementById('dayForecast');
        if (forecastDiv) {
            forecastDiv.innerHTML = '';
            data.forecast.forecastday.forEach(day => {
                let d = new Date(day.date);
                forecastDiv.innerHTML += `
                    <div class="forecast-item" style="display:flex; justify-content:space-between; align-items:center; padding:12px; border-bottom:1px solid #f1f1f1;">
                        <span style="width: 50px;">${d.toLocaleDateString('en-US', {weekday: 'short'})}</span>
                        <img src="https:${day.day.condition.icon}" width="35">
                        <span style="font-weight:bold; width: 50px; text-align:right;">${day.day.avgtemp_c}&deg;C</span>
                    </div>`;
            });
        }

        // --- F. HOURLY FORECAST ---
        const hourlyDiv = document.getElementById('hourlyForecast');
        if (hourlyDiv) {
            hourlyDiv.innerHTML = '';
            let hours = data.forecast.forecastday[0].hour;
            for(let i = 0; i < 24; i += 3) {
                let hr = hours[i];
                let time = new Date(hr.time).getHours();
                let ampm = time >= 12 ? 'PM' : 'AM';
                let displayTime = time % 12 || 12;
                hourlyDiv.innerHTML += `
                    <div class="card" style="min-width: 90px; text-align: center; padding: 15px; margin-right: 10px; flex-shrink: 0;">
                        <p style="font-size: 0.9rem;">${displayTime} ${ampm}</p>
                        <img src="https:${hr.condition.icon}" width="35" style="margin: 5px 0;">
                        <p style="font-weight:bold;">${hr.temp_c}&deg;C</p>
                    </div>`;
            }
        }

    } catch (error) {
        console.error("CraveCast Fetch Error:", error);
    }
}

// 3. EVENT LISTENERS
const searchBtn = document.getElementById('searchBtn');
if (searchBtn) {
    searchBtn.addEventListener('click', () => {
        const city = document.getElementById('city_input').value;
        if(city) getWeatherDetails(city);
    });
}

// Default call
window.onload = () => getWeatherDetails('Binangonan');