----reg1----

<?php
session_start();
$nameerr=$passworderr=$ageerr=$emailerr=$addresserr=$patherr="";
$nameerr=$_SESSION['nameerr'];
$passworderr=$_SESSION['passworderr'];
$ageerr=$_SESSION['ageerr'];
$emailerr=$_SESSION['emailerr'];
$addresserr=$_SESSION['addresserr'];
$patherr=$_SESSION['patherr'];

$name=$_SESSION['name'];
$password=$_SESSION['password'];
$age= $_SESSION['age'];
$email=$_SESSION['email'];
$address=$_SESSION['address'];
$path=$_SESSION['path'];


//echo $_SESSION['msg']; 
session_destroy();


?>
<html>
<head>
</head>
<body>
<table>
<form method="post" enctype="multipart/form-data" action="reg2.php">
  <tr>
    <td>name</td>
    <td><input type="text" name="name" value="<?= $name ?>"><?php echo $nameerr;?></td>
  </tr> 

  <tr>
    <td>Password</td>
    <td><input type="password" name="password" ><?php echo $passworderr;?></td>
  </tr> 
   
  <tr>
    <td>Age</td>
    <td><input type="text" name="age" value="<?= $age ?>"><?php echo $ageerr;?></td>
  </tr> 


  <tr>
    <td>Email</td>
    <td><input type="text" name="email" value="<?= $email ?>"><?php echo $emailerr;?></td>
  </tr> 


  <tr>
    <td>Address</td>
    <td><textarea name="address"><?php echo $address ?></textarea><?php echo $addresserr;?></td>
  </tr> 

  <tr>
    <td>Image</td>
    <td><input type="file" name="uploadedimage" value="<?= $path ?>"><?php echo $patherr;?></td>
  </tr> 

  
  <tr>
    <td><input type="submit" name="submit"></td>
    <td><input type="submit" name="submit1" value="Forgetpassword"></td>
  </tr> 

   

</form>
</table>
</body>

-----------reg2----------

<?php
  ini_set("display_errors", TRUE);
  error_reporting(E_ALL);
  session_start();
  $servername = "localhost";
  $username = "root";
  $password1 = "anktech@123";
  $dbname = "sandhya"; 
  $conn = new mysqli($servername, $username, $password1, $dbname);
  if ($conn->connect_error) 
    {
      die("Connection failed: " . $conn->connect_error);
    }
        
  
  $nameerr=$passworderr=$ageerr=$emailerr=$addresserr=$patherr="";
  $name=$password=$age=$email=$address=$path="";
  
   if ($_SERVER["REQUEST_METHOD"] == "POST") 
  {   
    if (empty($_POST["name"])) 
    {
      $nameerr="Name is required";
    }
    else
    {  
       $name=test_input($_POST["name"]);
        
        if (strlen($name)<6)
      {
        $nameerr= "It is less than  to 6 characters.";
      } 
        else 
      {
         $name=test_input($_POST["name"]);

        if (!preg_match("/^[a-zA-Z'-]+$/",$name)) 
        {
          $nameerr = "Invalid name format"; 
        }
        else
        {
         $name=test_input($_POST["name"]);
        }
      }
    
      $q3='SELECT *FROM `details` WHERE name="'.$name.'"';
      $results = $conn->query($q3) or die ($conn->error);
      $data=mysqli_fetch_array($results);
      if (mysqli_num_rows($results) > 0)
      {
        $nameerr="Username already exists";
      }
    }

     if (empty($_POST["password"])) 
    {
      $passworderr="Password is required";
    }
    else
    {
        $password=test_input($_POST["password"]);
    }
     if (empty($_POST["age"])) 
    {
      $ageerr="Age is required";
    }
    else
    {
        $age=test_input($_POST["age"]);

        if(!is_numeric($age))
      {
        $ageerr="Age must be numeric";
      }
      else
      {
        if($age>=18 && $age<=50)
        {
          $age=test_input($_POST["age"]);
        }
        else
        {
          $ageerr="Age must be between 18 to 50";
        }
      }

    }

    if (empty($_POST["email"])) 
    {
      $emailerr="Email is required";
    }
    else
    {
        $email=test_input($_POST["email"]);

       if (filter_var($email, FILTER_VALIDATE_EMAIL) ==TRUE) 
       {
         $email=test_input($_POST["email"]);
       }
       else
       {
          $emailerr = "Invalid email format";
       }
    }

     if (empty($_POST["address"])) 
    {
      $addresserr="Address is required";
    }
    else
    {
        $address=test_input($_POST["address"]);
    }
    if (empty($_FILES["uploadedimage"]["name"])) 
    {
      $patherr="Image is required";
    }
    else
    {
        $path=test_input($_FILES["uploadedimage"]["name"]);
        $allowex = array("gif", "jpeg", "jpg","png");
        $extension_arr = explode(".", $_FILES["uploadedimage"]["name"]);
        $extension = end($extension_arr);
        $image=$_FILES['uploadedimage']['name'];
        $tmp_name=$_FILES['uploadedimage']['tmp_name'];
        
        if(!in_array($extension,$allowex))
        {
           $patherr = "Image Type not supported";
        }
         else
         {

         }
    }



   
    if(!empty($nameerr) || !empty($passworderr)||!empty($ageerr)||
       !empty($emailerr)||!empty($addresserr)|| !empty($patherr))
    {
       $_SESSION['nameerr'] = $nameerr;
       $_SESSION['passworderr'] = $passworderr;
       $_SESSION['ageerr']=$ageerr;
       $_SESSION['emailerr']=$emailerr;
       $_SESSION['addresserr']=$addresserr;
       $_SESSION['patherr']=$patherr;

       $_SESSION['name'] = $name;
       $_SESSION['password'] = $password;
       $_SESSION['age']=$age;
       $_SESSION['email']=$email;
       $_SESSION['address']=$address;
       $_SESSION['path']=$path;
       header("location:reg1.php");
    }
      
      else
      {
        
        
        if ($conn->connect_error) 
        {
          die("Connection failed: " . $conn->connect_error);
        }
        
        $image=$_FILES['uploadedimage']['name'];
        $tmp_name=$_FILES['uploadedimage']['tmp_name'];

        $path= "/var/www/html/php/image/".$image;
    
        if(move_uploaded_file($tmp_name,$path))
        {
          $q2="INSERT INTO `details`(`id`, `name`, `password`, `age`,`email`, 
            `address`, `image_path`) VALUES ('','$name','$password','$age',
            '$email','$address','$image')";
           $conn->query($q2) or die ($conn->error);
           //$_SESSION['msg'] = "your registration has been successful";
           header("location:login.php");           
        }
        $conn->close();
    }

    if(isset($_POST['submit1']))
  {
     header("location:forgetpassword.php");
  }

}
  function test_input($data) 
{
   $data = stripslashes($data);
   $data = trim($data);
   $data = htmlspecialchars($data);      
   return $data;
}
?>

-----login-------
<?php
  session_start();
  ini_set("display_errors", TRUE);
  error_reporting(E_ALL);
  
  $servername = "localhost";
  $username = "root";
  $password1 = "anktech@123";
  $dbname = "sandhya"; 
  $conn = new mysqli($servername, $username, $password1, $dbname);
  if ($conn->connect_error) 
	{
	  die("Connection failed: " . $conn->connect_error);
	}
  $nameerr=$passworderr="";
  $name=$password="";

if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
     if (empty($_POST["name"])) 
    {
      $nameerr="Name is required";
    }
     else
    {
        $name=test_input($_POST["name"]);        
    }

       if (empty($_POST["password"])) 
    {
      $passworderr="Password is required";
    }
     else
    {
       $password=test_input($_POST["password"]);
    }
    if(!empty($name) && !empty($password)) 
    {

      $q3 = 'SELECT * FROM `details` WHERE name="'.$name.'" AND password="'.
              $password.'"';
        
        $results = $conn->query($q3) or die ($conn->error);
        $data=mysqli_fetch_array($results);

        if (mysqli_num_rows($results) > 0)
        {
        	$_SESSION['user_id'] = $data['id'];
        	header("location:viewdetails.php");
        }
        else
        {
        	echo "user does not exist";
        }
    }
}



  function test_input($data) 
{
   $data = stripslashes($data);
   $data = trim($data);
   $data = htmlspecialchars($data);      
   return $data;
}

?>
<html>
<head>
</head>
<body>
<table>
<form method="post" action="">
  <tr>
    <td>Name</td>
    <td><input type="text" name="name"><?php echo $nameerr ?></td>
  </tr> 

  <tr>
    <td>Password</td>
    <td><input type="password" name="password"><?php echo $passworderr ?></td>
  </tr> 

   <tr>
    <td><input type="submit" name="submit1" value="View details"></td>
  </tr> 
</form>
</table>
</body>
</html>

-------viewdetails---------

 <?php
 
  ini_set("display_errors", TRUE);
  error_reporting(E_ALL);                                 
  session_start();
  $servername = "localhost";
  $username = "root";
  $password1 = "anktech@123";
  $dbname = "sandhya"; 
  $conn = new mysqli($servername, $username, $password1, $dbname);
   if ($conn->connect_error) 
        
    {
      die("Connection failed: " . $conn->connect_error);
    }

  $user_id = 0;

  if(isset($_SESSION['user_id'])) 
  {
  	$user_id = $_SESSION['user_id'];
  }
  else
   {
  	header('location:login.php');
   }
    $q1="SELECT * FROM `details` WHERE id='".$user_id."'";
    $results = $conn->query($q1) or die ($conn->error);

    echo "<table border=1>";
    
    	echo "<tr>";
            
        echo "<th>id</th>";

        echo "<th>Name</th>";

        echo "<th>Age</th>";

        echo "<th>Email</th>";

        echo "<th>Address</th>";

        echo "<th>Image_Path</th>";

      echo "</tr>";

    while($data=mysqli_fetch_array($results))
    {
      echo "<tr>";

        echo "<td>$data[0]</td>
              <td>$data[1]</td>
              <td>$data[3]</td>
              <td>$data[4]</td>
              <td>$data[5]</td>
              <td><img src='image/".$data[6]."'height=10% width=10%></td>";
        echo "</tr>";
    }
        
        echo "<tr><td><a href='/php/logout.php'>SignOut</a></td></tr>";

        echo "</table>";

  ?>

  -------------logout----------
  <?php
    session_start();
  	unset($_SESSION['user_id']);
  	header('location:login.php');
?>
-------forget password---------
<?php
  session_start();
  ini_set("display_errors", TRUE);
  error_reporting(E_ALL);
  
  $servername = "localhost";
  $username = "root";
  $password1 = "anktech@123";
  $dbname = "sandhya"; 
  $conn = new mysqli($servername, $username, $password1, $dbname);
  if ($conn->connect_error) 
	{
	  die("Connection failed: " . $conn->connect_error);
	}
  $name=$password="";
  $emailerr="";
  $email="";


if ($_SERVER["REQUEST_METHOD"] == "POST") 
{   
   
    if (empty($_POST["email"])) 
    {
      $emailerr="Email is required";
    }
    else
    {
      $email=test_input($_POST["email"]);

      if (!filter_var($email, FILTER_VALIDATE_EMAIL) ==TRUE) 
      {
      $emailerr = "Invalid email format";
      }
    }
   
    
      $q1 = 'SELECT * FROM `details` WHERE  email="'.$email.'"';
      $results = $conn->query($q1) or die ($conn->error);
      $data=mysqli_fetch_array($results);
      if (mysqli_num_rows($results) > 0)
      {
        $password=$data["password"]; 
        $email=$data["email"]; 
        $subject="Verbazon.net - Password Request"; 
        //$header="From: neetu.mehta@anktech.co.in"; 
        echo $content="Your password is ".$password; 
        if(mail($email, $subject, $content))
        {
          echo  "An email containing the password has been sent to you"; 
        }
       
        else  
        { 
        echo "no such login in the system. please try again."; 
        } 
      }
      else
      {
        echo "user already exists";
      }
    
}
  function test_input($data) 
{
   $data = stripslashes($data);
   $data = trim($data);
   $data = htmlspecialchars($data);      
   return $data;
}

?>

<html>
<head>
</head>
<body>
<table>
<form method="post">
  <tr>
    <td>Email</td>
    <td><input type="text" name="email"><?php echo $emailerr?></td>
  </tr> 
 
  <tr>
    <td><input type="submit" name="submit"></td>
  </tr> 


</form>
<table>
</body>
</html>
