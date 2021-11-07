<?php
require_once("models/Survey.inc.php");
require_once("models/Response.inc.php");

class Database
{

	private $connection;

	/**
	 * Ouvre la base de donnÃ©es. Si la base n'existe pas elle
	 * est crÃ©Ã©e Ã  l'aide de la mÃ©thode createDataBase().
	 */
	public function __construct()
	{


		$this->connection = new PDO("sqlite:database.sqlite");
		if (!$this->connection) die("impossible d'ouvrir la base de donnÃ©es");

		$q = $this->connection->query('SELECT name FROM sqlite_master WHERE type="table"');

		if (count($q->fetchAll()) == 0) {
			$this->createDataBase();
		}
	}


	/**
	 * CrÃ©e la base de donnÃ©es ouverte dans la variable $connection.
	 * Elle contient trois tables :
	 * - une table users(nickname char(20), password char(50));
	 * - une table surveys(id integer primary key autoincrement,
	 *						owner char(20), question char(255));
	 * - une table responses(id integer primary key autoincrement,
	 *		id_survey integer,
	 *		title char(255),
	 *		count integer);
	 */
	private function createDataBase()
	{
		/* TODO  */
	}

	/**
	 * VÃ©rifie si un pseudonyme est valide, c'est-Ã -dire,
	 * s'il contient entre 3 et 10 caractÃ¨res et uniquement des lettres.
	 *
	 * @param string $nickname Pseudonyme Ã  vÃ©rifier.
	 * @return boolean True si le pseudonyme est valide, false sinon.
	 */
	private function checkNicknameValidity($nickname)
	{
		/* TODO  */
		if (preg_match('/[a-zA-Z]{3,10}/', $nickname)) {

			return true;
		}
		return false;
	}

	/**
	 * VÃ©rifie si un mot de passe est valide, c'est-Ã -dire,
	 * s'il contient entre 3 et 10 caractÃ¨res.
	 *
	 * @param string $password Mot de passe Ã  vÃ©rifier.
	 * @return boolean True si le mot de passe est valide, false sinon.
	 */
	private function checkPasswordValidity($password)
	{
		$uppercase = preg_match('@[A-Z]@', $password);
		$lowercase = preg_match('@[a-z]@', $password);
		$number    = preg_match('@[0-9]@', $password);

		if (!$uppercase || !$lowercase || !$number || strlen($password) < 8) {
			return false;
		}
		return true;
	}

	/**
	 * VÃ©rifie la disponibilitÃ© d'un pseudonyme.
	 *
	 * @param string $nickname Pseudonyme Ã  vÃ©rifier.
	 * @return boolean True si le pseudonyme est disponible, false sinon.
	 */
	private function checkNicknameAvailability($nickname)
	{
		// /* TODO  */
		$sql = "SELECT * FROM `USERS` WHERE `nickname` = :nickname";
		$query = $this->connection->prepare($sql);
		$query->bindValue(":nickname", $_POST['signUpLogin'], PDO::PARAM_STR);
		$query->execute();
		$user = $query->fetch(PDO::FETCH_ASSOC);
		if (!$user) {
			return true;
		}

		return false;
	}

	/**
	 * VÃ©rifie qu'un couple (pseudonyme, mot de passe) est correct.
	 *
	 * @param string $nickname Pseudonyme.
	 * @param string $password Mot de passe.
	 * @return boolean True si le couple est correct, false sinon.
	 */
	public function checkPassword($nickname, $password)
	{
		/* TODO  */
		if (isset($_POST['nickname']) && isset($_POST['password'])) {
			if (!empty($nickname) && !empty($password)) {
				$nickname = $_POST['nickname'];
				$password = $_POST['password'];
				//nettoyer
				$nickname = $this->connection->quote($nickname);
				// prÃ©parer la requÃªte
				$query = "SELECT password FROM USERS WHERE nickname = $nickname";
				// rÃ©cupÃ©rer le resultat
				$result = $this->connection->query($query);
				$user = $result->fetch(PDO::FETCH_ASSOC);
				if ($user) {
					return password_verify($password, $user['password']);
				}
			}
			return false;
		}

		return false;
	}

	/**
	 * Ajoute un nouveau compte utilisateur si le pseudonyme est valide et disponible et
	 * si le mot de passe est valide. La mÃ©thode peut retourner un des messages d'erreur qui suivent :
	 * - "Le pseudo doit contenir entre 3 et 10 lettres.";
	 * - "Le mot de passe doit contenir entre 3 et 10 caractÃ¨res.";
	 * - "Le pseudo existe dÃ©jÃ .".
	 *
	 * @param string $nickname Pseudonyme.
	 * @param string $password Mot de passe.
	 * @return boolean|string True si le couple a Ã©tÃ© ajoutÃ© avec succÃ¨s, un message d'erreur sinon.
	 */
	public function addUser($nickname, $password)
	{
		/* TODO  */
		if (!$this->checkNicknameValidity($nickname)) {
			return "Le pseudo doit contenir entre 3 et 10 lettres.";
		} else if (!$this->checkPasswordValidity($password)) {
			return "Le mot de passe doit contenir entre 3 et 10 caractÃ¨res.";
		} else if (!$this->checkNicknameAvailability($nickname)) {
			return "Le pseudo existe dÃ©jÃ .";
		}
		$password = password_hash($password, PASSWORD_DEFAULT);
		$sql = "INSERT INTO  USERS(nickname,password) VALUES(:nickname, :password)";
		$query = $this->connection->prepare($sql);
		$query->bindValue(":nickname", $nickname, PDO::PARAM_STR);
		$query->bindValue(":password", $password, PDO::PARAM_STR);
		$query->execute();
		if ($query->rowCount() > 0) {
			return true;
		} else {
			return "Erreur lors de l'insertion";
		}
	}

	/**
	 * Change le mot de passe d'un utilisateur.
	 * La fonction vÃ©rifie si le mot de passe est valide. S'il ne l'est pas,
	 * la fonction retourne le texte 'Le mot de passe doit contenir entre 3 et 10 caractÃ¨res.'.
	 * Sinon, le mot de passe est modifiÃ© en base de donnÃ©es et la fonction retourne true.
	 *
	 * @param string $nickname Pseudonyme de l'utilisateur.
	 * @param string $password Nouveau mot de passe.
	 * @return boolean|string True si le mot de passe a Ã©tÃ© modifiÃ©, un message d'erreur sinon.
	 */
	public function updateUser($nickname, $password)
	{
		/* TODO  */
		if(!$this->checkPasswordValidity($password)){
			return "Le mot de passe doit contenir entre 3 et 10 caractÃ¨res.";
		}
		$password = password_hash($password,PASSWORD_DEFAULT);
		$sql = "UPDATE users SET `password`=:password WHERE `nickname`=:nickname";
		$query = $this->connection->prepare($sql);
		$query->bindValue(":nickname",$nickname,PDO::PARAM_STR);
		$query->bindValue(":password",$password,PDO::PARAM_STR);
		$query->execute();
		if($query->rowCount()>0){
			return true;
		}
		return "voir base de donnÃ©e";
	}

	/**
	 * Sauvegarde un sondage dans la base de donnÃ©e et met Ã  jour les indentifiants
	 * du sondage et des rÃ©ponses.
	 *
	 * @param Survey $survey Sondage Ã  sauvegarder.
	 * @return boolean True si la sauvegarde a Ã©tÃ© rÃ©alisÃ©e avec succÃ¨s, false sinon.
	 */
	public function saveSurvey(&$survey)
	{
		/* TODO  */
	    // récupération de la question et du propriétaire 
	    $question = $survey->getQuestion();
	    $owner = $survey->getOwner();
	 
	    // query 
	    $sql = "INSERT INTO `surveys`(owner,question) VALUES('$owner','$question')";
		$result = $this->connection->exec($sql);
		
		if($result>0){
		    return true;
		}
		
	    
	    return FALSE;
	}

	/**
	 * Sauvegarde une rÃ©ponse dans la base de donnÃ©e et met Ã  jour son indentifiant.
	 *
	 * @param Survey $response RÃ©ponse Ã  sauvegarder.
	 * @return boolean True si la sauvegarde a Ã©tÃ© rÃ©alisÃ©e avec succÃ¨s, false sinon.
	 */
	private function saveResponse(&$response)
	{
		/* TODO  */
		return true;
	}

	/**
	 * Charge l'ensemble des sondages crÃ©Ã©s par un utilisateur.
	 *
	 * @param string $owner Pseudonyme de l'utilisateur.
	 * @return array(Survey)|boolean Sondages trouvÃ©s par la fonction ou false si une erreur s'est produite.
	 */
	public function loadSurveysByOwner($owner)
	{
		/* TODO  */
	}

	/**
	 * Charge l'ensemble des sondages dont la question contient un mot clÃ©.
	 *
	 * @param string $keyword Mot clÃ© Ã  chercher.
	 * @return array(Survey)|boolean Sondages trouvÃ©s par la fonction ou false si une erreur s'est produite.
	 */
	public function loadSurveysByKeyword($keyword)
	{
		/* TODO  */
		return array(
			new Survey("baba", "mon nom est Aboubacar"),
			new Survey("toto", "mon nom est toto")
		);
	}


	/**
	 * Enregistre le vote d'un utilisateur pour la rÃ©ponse d'indentifiant $id.
	 *
	 * @param int $id Identifiant de la rÃ©ponse.
	 * @return boolean True si le vote a Ã©tÃ© enregistrÃ©, false sinon.
	 */
	public function vote($id)
	{
		/* TODO  */
	}

	/**
	 * Construit un tableau de sondages Ã  partir d'un tableau de ligne de la table 'surveys'.
	 * Ce tableau a Ã©tÃ© obtenu Ã  l'aide de la mÃ©thode fetchAll() de PDO.
	 *
	 * @param array $arraySurveys Tableau de lignes.
	 * @return array(Survey)|boolean Le tableau de sondages ou false si une erreur s'est produite.
	 */
	private function loadSurveys($arraySurveys)
	{
		$surveys = array();
		/* TODO  */
		return $surveys;
	}

	/**
	 * Construit un tableau de rÃ©ponses Ã  partir d'un tableau de ligne de la table 'responses'.
	 * Ce tableau a Ã©tÃ© obtenu Ã  l'aide de la mÃ©thode fetchAll() de PDO.
	 *
	 * @param array $arraySurveys Tableau de lignes.
	 * @return array(Response)|boolean Le tableau de rÃ©ponses ou false si une erreur s'est produite.
	 */
	private function loadResponses(&$survey, $arrayResponses)
	{
		/* TODO  */
	}
}
