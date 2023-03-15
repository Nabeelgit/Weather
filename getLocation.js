function calculate_day(day){
  const d = new Date();
  d.setTime(d.getTime() + (day * 24 * 60 * 60 * 1000));
  return d.toUTCString();
}
function setCookie(cname, cvalue, expires) {
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}
function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(handlePosition, showError);
  } else { 
    alert("Geolocation is not supported by this browser.");
  }
}

function handlePosition(position) {
  const lat = position.coords.latitude;
  const long = position.coords.longitude;
  const api_uri = 'https://api.bigdatacloud.net/data/reverse-geocode-client?latitude='+lat+'&longitude='+long+'&localityLanguage=en';
  fetch(api_uri)
  .then((response) => response.json())
  .then((data) => {
    setCookie('location', data.locality, calculate_day(365));
    window.location.reload();
  });
}

function showError(error) {
  let returnString;
  switch(error.code) {
    case error.PERMISSION_DENIED:
        returnString = "You have to accept the agreement to get weather information about your area";
      break;
    case error.POSITION_UNAVAILABLE:
        returnString = "Location information is unavailable.";
      break;
    case error.TIMEOUT:
        returnString = "The request to get user location timed out.";
      break;
    case error.UNKNOWN_ERROR:
        returnString = "An unknown error occurred.";
      break;
  }
  alert(returnString);
}

getLocation();
