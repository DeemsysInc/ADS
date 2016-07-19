<?php 
/*** declare our interfaces ***/

/*** Interface for all Menus and assigning templates ***/
interface iMenus{
	//public function menuLinks($pMenuName);
	//public function menuTopIcons();
	//public function menuContent($pPageSlug);

}

/*** Interface for page builder ***/
interface iPageBuilder{
	public function pageHeader();
	public function pageContent($pAction);
	public function pageFooter();
	public function pageLeft();
	public function pageRight($pAction);
}

/*** Interface for all modules ***/
interface iConfig{
	public function config();
}

/*** Interface for all modules ***/
interface iModules{
	//public function modHome();
}

/*** Interface for all modules ***/
interface iCommon{
	//public function modCommon();
}

/*** Interface for all modules ***/
interface iPlugins{
	//public function slugSeperator();
	
}

?>