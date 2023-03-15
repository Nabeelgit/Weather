<!DOCTYPE html>
<html lang="en">
<!-- 
VIDEOS FROM YOUTUBE
https://www.youtube.com/watch?v=sGkh1W5cbH4
https://www.youtube.com/watch?v=jNEjw1fMk-8
https://www.youtube.com/watch?v=eTeD8DAta4c
https://www.youtube.com/watch?v=ipf7ifVSeDU
https://www.youtube.com/watch?v=xT_M7B6yeiI
 -->
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Weather</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./style.css">
    <?php
    error_reporting(0);
    $preferred = $_COOKIE['unit'] ?? 'f';
    function getLocation(){
        ?>
        <script src="./getLocation.js"></script>
        <?php
        return null;
    }
    $random_locations = ['London', 'Paris', 'Moscow', 'New Dehli', 'Tokyo', 'Hong Kong', 'Rome', 'Madrid', 'Lisbon', 'Stockholm', 'Warsaw', 'Miami', 'Los angeles', 'Cape town', 'Sydney', 'Manila', 'Brasilia', 'Riyadh', 'Jakarta'];
    $location = $_GET['location'] ?? $_COOKIE['location'] ?? getLocation() ?? $random_locations[array_rand($random_locations)];
    $url = 'https://www.google.com/search?num=1&hl=en&q=Weather+in+'.urlencode($location);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/111.0.0.0 Safari/537.36'));
    $exec = curl_exec($ch);
    if($exec === 0){
        echo 'error';
        exit;
    }
    curl_close($ch);
    $doc = new DOMDocument;
    @$doc->loadHTML($exec);
    $temp = $doc->getElementById('wob_tm')->textContent;
    $temp_c = $doc->getElementById('wob_ttm')->textContent;
    $time = $doc->getElementById('wob_dts')->textContent;
    $condition = $doc->getElementById('wob_dcp')->textContent;
    $percipitation = $doc->getElementById('wob_pp')->textContent;
    $humidity = $doc->getElementById('wob_hm')->textContent;
    $wind_speed = $doc->getElementById('wob_ws')->textContent;
    $wind_speed_km = $doc->getElementById('wob_tws')->textContent;
    $not_on_mobile = isset($_SERVER['HTTP_USER_AGENT']) && !(str_contains($_SERVER['HTTP_USER_AGENT'], 'IOS') || str_contains($_SERVER['HTTP_USER_AGENT'], 'Android'));
    $chosen = '';
    if($not_on_mobile){
        $vids = ['rain' => 'eTeD8DAta4c', 'sunny' => 'ipf7ifVSeDU', 'cloudy' => 'jNEjw1fMk-8', 'snowy' => 'sGkh1W5cbH4', 'thunderstorm' => 'xT_M7B6yeiI'];
        $condition = strtolower($condition);
        if(str_contains($condition, 'sun') || str_contains($condition, 'clear')){
            $chosen = $vids['sunny'];
        } else if(str_contains($condition, 'snow')){
            $chosen = $vids['snowy'];
        } else if(str_contains($condition, 'rain') || str_contains($condition, 'shower')){
            $chosen = $vids['rain'];
        } else if(str_contains($condition, 'thunder')){
            $chosen = $vids['thunderstorm'];
        } else {
            $chosen = $vids['cloudy'];
        }
        ?>
        <style>
            .container {
                position: absolute;
                color: #FFFFFF;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -50%);
                backdrop-filter: blur(10px);
                border: 1px solid rgba(0, 0, 0, .4);
                border-radius: 1rem;
            }
        </style>
        <?php
    } else {
        ?>
        <style>
            .container {
                color: black;
                height: 86vh;
            }
            svg {
                fill: black;
            }
        </style>
        <?php
    }
    $to_use = [];
    if($preferred === 'f'){
        $to_use = ['temp' => $temp, 'wind' => $wind_speed];
    } else {
        $to_use = ['temp' => $temp_c, 'wind' => $wind_speed_km];
    }
    ?>
    <style>
        .<?php echo $preferred ?> {
            font-weight: bold;
        }
    </style>
</head>
<body>
<?php
if($not_on_mobile){
    ?>
    <div class="video-container">
        <iframe src="https://www.youtube.com/embed/<?php echo $chosen?>?&controls=0&start=32&autoplay=1&playsinline=1&mute=1&loop=1"></iframe>
    </div>
    <?php
}
?>

    <div class="container">
        <?php
        if($temp === null){
            echo '<span class="error-msg">No weather data for this area</span>';
            exit;
        }
        ?>
        <div class="weather-display">
            <div style="margin-right: 1rem; display: flex;align-items: center;justify-content: space-around">
                <div class="temp">
                    <span id="temperature"><?php echo $to_use['temp']?></span>
                    <div class="units">
                        <p class="f" id="m">F</p>
                        <hr width="10px">
                        <p class="c" id="km">C</p>
                    </div>
                </div>
                <div class="bottom-info">
                    <span><?php echo $location?> <span id="change_loc"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M362.7 19.3L314.3 67.7 444.3 197.7l48.4-48.4c25-25 25-65.5 0-90.5L453.3 19.3c-25-25-65.5-25-90.5 0zm-71 71L58.6 323.5c-10.4 10.4-18 23.3-22.2 37.4L1 481.2C-1.5 489.7 .8 498.8 7 505s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L421.7 220.3 291.7 90.3z"/></svg></span> - <?php echo $time?></span>
                    <br>
                    <span><?php echo $condition?></span>
                </div>
            </div>
            <div class="other-info">
                <div>
                    <h2>Percipitation</h2>
                    <span><?php echo $percipitation?></span>
                </div>
                <div>
                    <h2>Humidity</h2>
                    <span><?php echo $humidity?></span>
                </div>
                <div>
                    <h2>Wind speed</h2>
                    <span id="wind"><?php echo $to_use['wind']?></span>
                </div>
            </div>
        </div>
    </div>
    <script>
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
            let expires = "expires="+d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }
        const values = {
            temp_f: '<?php echo $temp?>',
            temp_c: '<?php echo $temp_c?>',
            wind_m: '<?php echo $wind_speed?>',
            wind_km: '<?php echo $wind_speed_km?>'
        }
        const units_p = document.querySelectorAll('.units p');
        const temperature_disp = document.getElementById('temperature');
        const wind_disp = document.getElementById('wind');
        units_p.forEach((p) => {
            p.addEventListener('click', function(){
                temperature_disp.innerText = values['temp_'+this.getAttribute('class')];
                wind_disp.innerText = values['wind_'+this.id];
                units_p.forEach((para) => {
                    para.style.fontWeight = 'normal';
                })
                this.style.fontWeight = 'bold';
                setCookie('unit', this.getAttribute('class'), 200);
            })
        })
        const change_loc = document.getElementById('change_loc');
        change_loc.addEventListener('click', function(){
            let new_loc = prompt('Enter a location');
            if(new_loc !== null && new_loc !== undefined && new_loc.trim() !== ''){
                let params = new URLSearchParams(window.location.search);
                params.set('location', new_loc);
                window.location = window.location.origin + window.location.pathname + '?' +params.toString();
            }
        })
    </script>
</body>
</html>