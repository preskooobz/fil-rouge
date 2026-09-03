<?php 
// inclure le fichier de connexion à la base de données
include_once 'db.php';

// ConnexionBD() est une fonction qui permet de se connecter à la base de données
function connexionBD() {
    // Réutilise la connexion PostgreSQL partagée définie dans db.php
    return getPdoConnection();
}


// La fonction ConnexionEnseignant() permet de connecter l'administrateur
function ConnexionEnseignant($login, $password) {
    $bd = connexionBD();
    $query = "SELECT * FROM enseignant WHERE login = :login AND password = :password";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
} 


// La fonction ConnexionEtudiant() permet de connecter l'étudiant
function ConnexionEtudiant($login, $password) {
    $bd = connexionBD();
    $query = "SELECT * FROM etudiant WHERE login = :login AND password = :password";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// la fonction verifierConnexion() permet de vérifier les informations de connexion d'un utilisateur
function verifierConnexion($email, $mot_de_passe) {
    $bd = connexionBD();
    $query = "SELECT * FROM utilisateurs WHERE email = :email";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($mot_de_passe, $user['mot_de_passe'])) {
        return $user;
    }

    return false;
}

// la fonction ajouterEtudiant() permet a l'administrateur d'ajouter un étudiant
function ajouterEtudiant($nom, $prenom, $login, $password) {
    $bd = connexionBD();
    $query = "INSERT INTO etudiant (nom, prenom, login, password) VALUES (:nom, :prenom, :login, :password)";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':prenom', $prenom);
    $stmt->bindParam(':login', $login);
    $stmt->bindParam(':password', $password);
    return $stmt->execute();
}

// la fonction VoirQCM() permet de récupérer les QCM auxquels l'étudiant a accès
function VoirQCM($id) {
    $bd = connexionBD();
    $query = "SELECT * FROM qcm WHERE id_etudiant = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// la fonction creerQCM() permet a l'administrateur de créer un QCM
function creerQCM($nom, $description, $date_debut, $date_fin) {
    $bd = connexionBD();
    $query = "INSERT INTO qcm (nom, description, date_debut, date_fin) VALUES (:nom, :description, :date_debut, :date_fin)";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date_debut', $date_debut);
    $stmt->bindParam(':date_fin', $date_fin);
    return $stmt->execute();
}
// la fonction getQCM() permet de récupérer les informations d'un QCM
function getQCM($id) {
    $bd = connexionBD();
    $query = "SELECT * FROM qcm WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// la fonction getAllQCMs() permet de récupérer tous les QCMs
function getAllQCMs() {
    $bd = connexionBD();
    $query = "SELECT * FROM qcms";
    $stmt = $bd->prepare($query);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// la fonction modifierQCM() permet a l'administrateur de modifier les informations d'un QCM
function modifierQCM($id, $nom, $description, $date_debut, $date_fin) {
    $bd = connexionBD();
    $query = "UPDATE qcm SET nom = :nom, description = :description, date_debut = :date_debut, date_fin = :date_fin WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':nom', $nom);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':date_debut', $date_debut);
    $stmt->bindParam(':date_fin', $date_fin);
    return $stmt->execute();
}
// la fonction supprimerQCM() permet a l'administrateur de supprimer un QCM
function supprimerQCM($id) {
    $bd = connexionBD();
    $query = "DELETE FROM qcm WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
// la fonction ajouterQuestion() permet a l'administrateur d'ajouter une question
function ajouterQuestion($id_qcm, $question) {
    $bd = connexionBD();
    $query = "INSERT INTO question (id_qcm, question) VALUES (:id_qcm, :question)";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id_qcm', $id_qcm);
    $stmt->bindParam(':question', $question);
    return $stmt->execute();
}
// la fonction getQuestion() permet de récupérer les informations d'une question
function getQuestion($id) {
    $bd = connexionBD();
    $query = "SELECT * FROM question WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// la fonction modifierQuestion() permet a l'administrateur de modifier les informations d'une question
function modifierQuestion($id, $id_qcm, $question) {
    $bd = connexionBD();
    $query = "UPDATE question SET id_qcm = :id_qcm, question = :question WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':id_qcm', $id_qcm);
    $stmt->bindParam(':question', $question);
    return $stmt->execute();
}
// la fonction supprimerQuestion() permet a l'administrateur de supprimer une question
function supprimerQuestion($id) {
    $bd = connexionBD();
    $query = "DELETE FROM question WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
// la fonction ajouterReponse() permet a l'administrateur d'ajouter une reponse
function ajouterReponse($id_question, $reponse, $correcte) {
    $bd = connexionBD();
    $query = "INSERT INTO reponse (id_question, reponse, correcte) VALUES (:id_question, :reponse, :correcte)";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id_question', $id_question);
    $stmt->bindParam(':reponse', $reponse);
    $stmt->bindParam(':correcte', $correcte);
    return $stmt->execute();
}
// la fonction getReponse() permet de récupérer les informations d'une reponse
function getReponse($id) {
    $bd = connexionBD();
    $query = "SELECT * FROM reponse WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}
// la fonction modifierReponse() permet a l'administrateur de modifier les informations d'une reponse
function modifierReponse($id, $id_question, $reponse, $correcte) {
    $bd = connexionBD();
    $query = "UPDATE reponse SET id_question = :id_question, reponse = :reponse, correcte = :correcte WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':id_question', $id_question);
    $stmt->bindParam(':reponse', $reponse);
    $stmt->bindParam(':correcte', $correcte);
    return $stmt->execute();
}
// la fonction supprimerReponse() permet a l'administrateur de supprimer une reponse
function supprimerReponse($id) {
    $bd = connexionBD();
    $query = "DELETE FROM reponse WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
// la fonction getResultats() permet de récupérer les resultats d'un etudiant
function getResultats($id) {
    $bd = connexionBD();
    $query = "SELECT * FROM resultats WHERE id_etudiant = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// la fonction ajouterResultat() permet a l'administrateur d'ajouter un resultat
function ajouterResultat($id_etudiant, $id_qcm, $score) {
    $bd = connexionBD();
    $query = "INSERT INTO resultats (id_etudiant, id_qcm, score) VALUES (:id_etudiant, :id_qcm, :score)";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id_etudiant', $id_etudiant);
    $stmt->bindParam(':id_qcm', $id_qcm);
    $stmt->bindParam(':score', $score);
    return $stmt->execute();
}
// la fonction getNotes() permet de récupérer les notes d'un etudiant
function getNotes($id) {
    $bd = connexionBD();
    $query = "SELECT * FROM notes WHERE id_etudiant = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// la fonction ajouterNote() permet a l'administrateur d'ajouter une note
function ajouterNote($id_etudiant, $note) {
    $bd = connexionBD();
    $query = "INSERT INTO notes (id_etudiant, note) VALUES (:id_etudiant, :note)";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id_etudiant', $id_etudiant);
    $stmt->bindParam(':note', $note);
    return $stmt->execute();
}
// la fonction modifierNote() permet a l'administrateur de modifier une note
function modifierNote($id, $id_etudiant, $note) {
    $bd = connexionBD();
    $query = "UPDATE notes SET id_etudiant = :id_etudiant, note = :note WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    $stmt->bindParam(':id_etudiant', $id_etudiant);
    $stmt->bindParam(':note', $note);
    return $stmt->execute();
}
// la fonction supprimerNote() permet a l'administrateur de supprimer une note
function supprimerNote($id) {
    $bd = connexionBD();
    $query = "DELETE FROM notes WHERE id = :id";
    $stmt = $bd->prepare($query);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}
function getEnseignantById($id, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE id = ? AND role = 'enseignant'");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


//la fonction getEtudiantById() permet de récupérer les informations d'un étudiant par son ID
function getEtudiantById($id, $pdo) {
    $stmt = $pdo->prepare("SELECT * FROM etudiants WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

?>