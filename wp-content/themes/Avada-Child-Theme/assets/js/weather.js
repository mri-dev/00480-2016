var Weather=function(a){function d(b){({city:qcity,data:null});a.get(api_daily,{APPID:apikey,cnt:c.dailyDays,units:c.units,q:qcity},function(a){b(a)})}function e(b){({city:qcity,data:null});a.get(api_current,{APPID:apikey,units:c.units,q:qcity},function(a){b(a)})}var b={},c={units:"metric",dailyDays:4};return qcity=!1,apikey="f9521e11ae77b6c41f76016c0852cf82",api_daily="http://api.openweathermap.org/data/2.5/forecast/daily",api_current="http://api.openweathermap.org/data/2.5/weather",b.getDailyWeather=function(a,b){qcity=a,d(function(a){b(a)})},b.getCurrentWeather=function(a,b){qcity=a,e(function(a){b(a)})},b}(jQuery);