<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/Login&Register.css">
    <title>Login & Register</title>
</head>
<body>
    <div class="navbar">
        <div class="sideLeft">
            <img class="logo" src="../Images/EduchainLogo-BlackV.png" alt="Logo">
            <div class="nav-text">EDUChain</div>
        </div>
        <ul class="sideRight">
            <li>
                <a class="nav-button" href="#">
                    <div>Homepage</div>
                    <div class="icon"></div>
                </a>     
            </li>
            <li>
                <a class="nav-button" href="#">
                    <div>Accedi</div>
                    <div class="icon"></div>
                </a>    
            </li>
        </ul>
    </div>
    <div class='container'>
        <div class='card' id='card'>
            <div class='cardHeader' id="formType" value='Registrati'>
                Registrati
            </div>
            <form action='' method='post'>
                <div class='cardContent'>
                    <div class='username'>
                        <div class='formTitle'>Username:</div>
                        <input class='typeText' type='text' name='UserID' required>
                    </div>
                    <div class='password'>
                        <div class='formTitle'>Password:</div>
                        <input class='typeText' type='password' name='Password' required>
                    </div>
                </div>
                <div class='cardButtons'>
                    <input class='typeSubmit' type='submit' name='Login' value='Invia'>
                    <div class='trigger' onclick='changeCard()'>Non hai ancora l'account? Clicca qui!</div>
                </div>
            </form>
        </div>
    </div>
    <script>
        function changeCard(){
            if(document.getElementById("formType").getAttribute('value')=="Registrati"){
                document.getElementById("card").innerHTML = `<div class='cardHeader' id='formType' value='Accedi'>
                                                                    Accedi
                                                                </div>
                                                                <form action='' method='post'>
                                                                    <div class='cardContent'>
                                                                        <div class='username'>
                                                                            <div class='formTitle'>Username:</div>
                                                                            <input class='typeText' type='text' name='UserID' required>
                                                                        </div>
                                                                        <div class='password'>
                                                                            <div class='formTitle'>Password:</div>
                                                                            <input class='typeText' type='password' name='Password' required>
                                                                        </div>
                                                                    </div>
                                                                    <div class='cardButtons'>
                                                                        <input class='typeSubmit' type='submit' name='Login' value='Invia'>
                                                                        <div class='trigger' onclick='changeCard()'>Non hai ancora l'account? Clicca qui!</div>
                                                                    </div>
                                                                </form>`;
            }else{
                console.log(document.getElementById("formType").getAttribute('value'));
            }
        }
    </script>
</body>
</html>