<?php
	include('config.php');
	include(mnminclude.'html1.php');
	do_header(_('FAQ') . ' | ' . _('titulares'));
	$q = 1;
?>
<div id="singlewrap">
<h2 class="faq-title">Las preguntas presuntamente frecuentes</h2>
<div class="faq" style="margin: 0 30px 75px 150px;">
<ol>
<li id="<?php echo "q$q";$q++;?>">
<h4>¿Qué es esto?</h4>
<p>Una red social organizada por plataformas que gestionan los propios usuarios</p>
</li>



</ol>

</div>
</div>
<?php

	do_footer_menu();
	do_footer();
