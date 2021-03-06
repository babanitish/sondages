<?php 

require_once("models/Model.inc.php");
require_once("actions/Action.inc.php");

class LoginAction extends Action {

	/**
	 * Traite les données envoyées par le visiteur via le formulaire de connexion
	 * (variables $_POST['nickname'] et $_POST['password']).
	 * Le mot de passe est vérifié en utilisant les méthodes de la classe Database.
	 * Si le mot de passe n'est pas correct, on affecte la chaîne "erreur"
	 * à la variable $loginError du modèle. Si la vérification est réussie,
	 * le pseudo est affecté à la variable de session et au modèle.
	 *
	 * @see Action::run()
	 */
	public function run() {
		/* TODO  */
		$this->setModel(new Model);
		$checkPass = $this->database->checkPassword($_POST['nickname'], $_POST['password']);
		if(!empty($_POST['nickname']) && !empty($_POST['password']) && $checkPass){
			$this->getModel()->setLogin($_POST['nickname']);
			$this->setSessionLogin($_POST['nickname']);
		}else{
			$this->getModel()->setLoginError("erreur");
		}
		$this->setView(getViewByName('Default'));
	}

}

?>
