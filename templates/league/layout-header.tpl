<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
  <meta content="text/html; charset=UTF8" http-equiv="content-type">
  <meta name="viewport" content="width=device-width, initial-scale=1{% if globals.mobile %}, user-scalable=no{% endif %}"/>
  {% if globals.favicon %}
	<link rel="shortcut icon" href="{{globals.base_static}}{{globals.favicon}}" type="image/x-icon"/>
{% else %}
	<link rel="shortcut icon"  href="{{globals.base_static}}img/favicons/logo_196x196.png" type="image/png" sizes="196x196"/>
	<link rel="shortcut icon"  href="{{globals.base_static}}img/favicons/logo_128x128.png" type="image/png" sizes="128x128"/>
	<link rel="shortcut icon"  href="{{globals.base_static}}img/favicons/logo_64x64.png" type="image/png" sizes="64x64"/>
	<link rel="shortcut icon" href="{{globals.base_static}}img/favicons/logo_16x16.png" type="image/png" sizes="16x16"/>
{% endif %}
  <link rel="stylesheet" href="{{globals.base_static}}css/es/nivea_2012.css" type="text/css" media="screen" />
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">

  <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7/jquery.min.js" type="text/javascript"></script> 
  <script src="{{globals.base_url}}js/{{globals.js_main}}" type="text/javascript"></script>
  <title>Quiniela Yabber</title>

</head>



<script src="/js/league.js"></script>
<script type="text/javascript">
base_key="{{ globals.security_key }}";
user_id = {{ current_user.user_id }};
user_login = '{{ current_user.user_login }}';
mobile_client = {{ globals.mobile }};
$(function() {
	var vstatus = ["{% trans _("¡votado!") %}", "{% trans _("gana") %}"],
		url	 = "{{globals.base_url}}backend/league_vote.php?";
    league_init(vstatus, url);
});

</script>



<body>

<div id="terms" style="display:none">
Ya sabemos lo que te gusta votar y que sabes quién va a ganar cada partido de liga, así que <b>bienvenido a la porra de liga de menéame deportes</b>, que hemos programado con el patrocinio de NIVEA FOR MEN como puedes ver.<br /><br />Menea el equipo ganador o la casilla de empate, y veremos si la comunidad menéame acierta más con la liga que los locutores deportivos.<br /><br />Sólo puedes votar una vez por partido, tienes 5 minutos para cambiar tu voto, y <b>necesitas un usuario en menéame</b>. Si aún no lo tienes puedes crearlo <b><a href="http://{{globals.server_name}}/register.php">aquí</a></b>; cuando lo tengas, si no puedes votar asegúrate que has hecho <b><a href="http://{{globals.server_name}}/login.php?return=%2Fnivea%2F">login</a></b>.<br /><br />Podrás votar cada partido desde que abramos las votaciones hasta una hora antes del inicio del partido.<br /><br />
    {% if accepted_terms === FALSE %}
	<form method="POST">
		<input type="submit" name="terms" value="{% trans _("Acepto") %}" />
		<input type="submit" name="terms" value="{% trans _("Rechazo") %}" />
	</form>
    {% endif %}
</div>

<div id="header-top">
<div class="back"><a href="http://yabber.us"> <i class="fa fa-chevron-left"></i> Yabber</a></div>
  <div class="jornada">Jornada 10</div>
  {% include "header_userinfo.html" %}
</div>

  <div class="mnm-logo">
	 <!-- <a href="http://{{globals.server_name}}">
		<img src="{{globals.base_static}}img/nivea_2012/mnmd_01.png" widht="150" height="45" alt="menéame deportes" title="ir a menéame deportes" />
	 </a> -->
	 <div class="title--shadow">
	 <div class="title">Quiniela Yabber</div>
	 </div>

  </div>

<!-- <div class="bases">
	<p><a id="bases" href="#">consultar las instrucciones</a></p>
</div>
 -->