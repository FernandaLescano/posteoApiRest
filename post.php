<?php
include "config.php";
include "utils.php";


$dbConn = connect($db);

// listar todos los posts a solo uno 


if($_SERVER['REQUEST_METHOD']== 'GET'){

    if(isset($_GET['id'])){

        //Mostrar un post 

        $sql = $dbConn->prepare("SELECT * FROM posts WHERE id=:id");
        $sql->bindValue(':id',$_GET['id']);
        $sql->execute();
        header('HTTP/1.1 200 OK');
        echo json_encode( $sql->fetch(PDO::FETCH_ASSOC));
        exit();
    } else {

        //mostrar una lista de post 

        $sql = $dbConn->prepare('SELECT * FROM posts');
        $sql->execute();
        $sql->fetch(PDO::FETCH_ASSOC);
        header('HTTP/1.1 200 OK');
        echo json_encode($sql->fetchAll());
        exit();
    }
}

//crear un nuevo post
if($_SERVER['REQUEST_METHOD']== 'POST'){

    $input = $_POST;
    $sql = "INSERT INTO posts
    (title, estatus, content, usuario_id)
    
    VALUES 
    (:title, :estatus, :content, :usuario_id)";

    $statement = $dbConn->prepare($sql);
    bindAllValues($statement, $input);
    $statement->execute();
    $postId = $dbConn->lastInsertId();
    if($postId){

        $input['id'] = $postId;
        header('HTTP/1.1 222 OK');
        echo json_encode( $input);
        exit();
    }
    
}


//borrar
if($_SERVER['REQUEST_METHOD']== 'DELETE'){

    $id = $_GET['id'];
    $statement = $dbConn->prepare("DELETE * FROM posts where id=:id");
    $statement->bindValue(':id',$id);
    $statement->execute();
    header('HTTP/1.1 200 OK');
    exit();

}

//actualizar
if($_SERVER['REQUEST_METHOD']== 'DELETE'){

    $input = $_GET;
    $postId = $input['id'];
    $fields = getParams($input);
    
    $sql = "
    
    UPDATE posts
    SET $fields
    WHERE id='$postId'
    ";
 
        $statement = $dbConn->prepare($sql);
        bindAllValues($statement, $input);

        $statement->execute();
        header('HTTP/1.1 200 OK');
        exit();

}




?>

