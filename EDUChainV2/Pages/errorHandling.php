<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../Styles/errorHandling.css">
    <title>Pagina Errori</title>
</head>
<body>
    <div class="container">
        <div class="intestazione">Pagina per la gestione degli errori</div>
            <!-- PROVA ERRORE PER STYLING
            <div class="error-card">
                <div class='titoloErrore'>Password non uguali</div>
                <div class='corpoErrore'>Assicurati di inserire due password uguali all'interno dei due campi.</div>
                <form action='Login&Register.php' method='post' class='button-container'>
                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                </form>
            </div> -->
            <div class="error-card">
            <?php
                if(isset($_SESSION["errorType"]) and $_SESSION["errorType"]!==0){
                    switch($_SESSION["errorType"]){
                        case 2:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Password non uguali</div>";
                            echo "<div class='corpoErrore'>Assicurati di inserire due password uguali all'interno dei due campi.</div>";
                            echo "<form action='Login&Register.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                        case 3:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Account con nome utente già esistente</div>";
                            echo "<div class='corpoErrore'>Inserisci un nome utente diverso, il tuo l'ha già preso qualcun'altro.</div>";
                            echo "<form action='Login&Register.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                        case 4:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Problemi con il server</div>";
                            echo "<div class='corpoErrore'>Possibile server non acceso o non funzionante.</div>";
                            echo "<form action='Login&Register.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Fai login o torna alla dashboard</button>
                                </form>";
                            break;
                        case 6:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Generazione negativa di monete</div>";
                            echo "<div class='corpoErrore'>Per piacere generare una somma di monete al minimo pari a zero</div>";
                            echo "<form action='PersonalDashboard/personalDashboardGenera.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                        case 7:
                            echo "<div class='titoloErrore'>Non disponi di un saldo abbastanza grande</div>";
                            echo "<div class='corpoErrore'>Per piacere, assicurarsi di possedere abbastanza monete da sostenere la transazione desiderata</div>";
                            echo "<form action='PersonalDashboard/personalDashboardRitira.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                        case 8:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Numero di monete da ritirare con valore negativo</div>";
                            echo "<div class='corpoErrore'>Per piacere inserire una somma da ritirare che sia maggiore o uguale a zero.</div>";
                            echo "<form action='PersonalDashboard/personalDashboardRitira.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                        case 9:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Username cercato inesistente.</div>";
                            echo "<div class='corpoErrore'>Per piacere assicurarsi di aver inserito uno username a cui inviare soldi, realmente esistente</div>";
                            echo "<form action='PersonalDashboard/personalDashboardInvia.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                        case 10:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Saldo insufficiente</div>";
                            echo "<div class='corpoErrore'>Il tuo saldo è insufficiente per sostenere la transazione che hai appena cercato di eseguire.</div>";
                            echo "<form action='PersonalDashboard/personalDashboardInvia.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                        case 11:
                            $_SESSION["errorType"] = 0;
                            echo "<div class='titoloErrore'>Importo nella transazione non valido</div>";
                            echo "<div class='corpoErrore'>Per piacere, inserire un'importo positivo.</div>";
                            echo "<form action='PersonalDashboard/personalDashboardInvia.php' method='post' class='button-container'>
                                    <button class='resolve-button' type='submit' name='Return' value='Invia'>Riprova</button>
                                </form>";
                            break;
                    }
                }else{
                    echo "<script type='text/javascript'> document.location = 'Login&Register.php'; </script>";
                }
            ?>
            </div>
        </div>  
    </div>
</body>
</html>