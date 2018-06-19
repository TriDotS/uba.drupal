<div id="app">
	<ressourcen-auswertung2 id="<?php print $variables['indicator_id']; ?>" url="http://uba.webshox.org/api/indicators/" :series='<?php print json_encode($variables['preprocessed_indicator']->series); ?>' :categories='<?php print json_encode($variables['preprocessed_indicator']->categories); ?>' name='<?php print $variables['preprocessed_indicator']->title->name; ?>'></ressourcen-auswertung2>
</div>
