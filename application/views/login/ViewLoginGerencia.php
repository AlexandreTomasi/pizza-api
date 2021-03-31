<!DOCTYPE html>
<html lang="en">
    <style>
        body {
            padding-top: 40px;
            padding-bottom: 40px;
            background-color: #f6f6f6;
        }

        .form {
            max-width: 330px;
            padding: 15px;
            margin: 0 auto;
        }
        .form .form-heading {
            margin-bottom: 20px;
            text-align: center;
        }
        .form .form-control {
            position: relative;
            font-size: 16px;
            height: auto;
            padding: 10px;
            -webkit-box-sizing: border-box;
            -moz-box-sizing: border-box;
            box-sizing: border-box;
        }
        .form .form-control:focus {
            z-index: 2;
        }
        .form input[type="text"] {
            margin-bottom: -1px;
            border-radius: 0;
        }
        .form input[type="password"] {
            margin-bottom: 10px;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .form-fields input[type="text"]:first-child {
            border-top-left-radius: 4px;
            border-top-right-radius: 3px;
        }
    </style>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>
    <link rel="stylesheet" href="<?=base_url("css/bootstrap.css")?>">
</head>
<body>
    <div class="container">
        <?php if( $this->session->flashdata("erro") ) :?>
            <p class="alert alert-danger" > <?= $this->session->flashdata("erro") ?></p>
        <?php  endif?>
        <table BORDER=1 class="table" > 
            <?php 
                echo "<a href='http://skybots.com.br/'>Página Inicial</a>"; 
            ?>
        </table>   
            <center><h2>Página de login de acesso somente pela gerência</h2><center>
            
            <div class="form-fields">
                <?php
                    echo form_open("logar/autenticarGerencia",array("class"=>"form"));
                        echo form_input(array(
                            "name" => "email",
                            "id" =>"email",
                            "type" =>"text",
                            "placeholder" => "Email",
                            "class" => "form-control",
                            "maxlength" => "255"
                        ));
                        echo form_input(array(
                            "name" => "senha",
                            "id" =>"senha",
                            "type" =>"password",
                            "placeholder" => "Senha",
                            "class" => "form-control",
                            "maxlength" => "255"
                        ));

                        echo form_button(array(
                            "type" => "submit",
                            "content" =>"Login",
                            "left" => "50%",
                            "class" => "btn btn-lg btn-primary btn-block"
                        ));       
                        echo form_close();
                ?>
            </div>
        <p class="text-center" style="margin-top:10px;">
        </p>
       
    </div> 
</body>
<script>

    function login() {
      UserApp.User.login({
        login: document.getElementById("email").value,
        password: document.getElementById("senha").value
      }, function(error, result) {
        if (error) {
          alert("Error: " + error.message);
        } else {
          // The user is logged in. Now get the user...
          UserApp.User.get({ user_id: "self" }, function(error, user) {
            if (error) {
              alert("Error: " + error.message);
            } else {
              alert("Welcome back, " + user[0].first_name + "!");
            }
          });
        }
      });

      return false;
    }
</script>
</html>