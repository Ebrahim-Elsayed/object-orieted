<?php 

require '../classes/dbClass.php';
require '../classes/validateClass.php';


if ($_SERVER["REQUEST_METHOD"] == "POST" ) {
  
  #create object
  $validate = new validator;
  
  #clean inputs
  $title   = $validate->Clean($_POST['title']);
  $content = $validate->Clean($_POST['content']);

  # Image File Data  .... 
  $file_tmp  =  $_FILES['image']['tmp_name'];
  $file_name =  $_FILES['image']['name'];  
  $file_size =  $_FILES['image']['size'];
  $file_type =  $_FILES['image']['type']; 

  $file_ex   = explode('.',$file_name);
  $updated_ex = strtolower(end($file_ex));
  
  #validate inputs

  $errors = [];

  #validate title

  if (!$validate->validate($title , 1)) {
    $errors['title'] = "Field Required";
  }elseif(!$validate->validate($title,6)){
    $errors['title'] = "Invalid String";
  }  
  
  #validate content

  if (!$validate->validate($content , 1)) {
    $errors['content'] = "Field Required";
  }elseif(!$validate->validate($content,4)){
    $errors['content'] = "Invalid Length , Length Must Be >= 50 ch";
  }  

  #Validate Image 
  if(!$validate->validate($file_name,1)){
    $errors['Image'] = "Field Required";
  }elseif(!$validate->validate($updated_ex,8)){
    $errors['Image'] = "Invalid Extension";
  }
  
  if(count($errors) > 0){
    foreach ($errors as $key => $error) {
      echo "* " . $key . " : " . $error . "<br>";
    } 
  }else{

    # Upload Image ..... 
    $finalName = rand().time().'.'.$updated_ex;

    $disPath = './uploads/'.$finalName;

    #db
    $db     = new Database;
    $sql    = "INSERT into blog ( title , content , image) VALUES ('$title' , '$content' , '$finalName')"; 
    $result = $db->doQuery($sql);

    if($result){
      echo 'Raw Inserted';
    }else{
      echo'Error Try Again';
    }

  }

}




?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Register</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</head>
<body>

<div class="container">
  <h2>Create Blog</h2>
  
  
  <form   action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>"  method="post" enctype="multipart/form-data">


  <div class="form-group">
    <label>Title</label>
    <input type="text" class="form-control" name="title" placeholder="Enter title">
  </div>


  <div class="form-group">
    <label for="exampleInputEmail">content</label>
    <input type="text"   class="form-control"  name="content" placeholder="Enter content">
  </div>

  <div class="form-group">
      <label >Image</label> <br>
      <input type="file" name="image">
  </div>
 
  <button type="submit" class="btn btn-primary">Save</button>
</form>
</div>

</body>
</html>





