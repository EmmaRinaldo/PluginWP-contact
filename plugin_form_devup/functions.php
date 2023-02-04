<?php

// Créer la table

function wf_create_subscribers_table(){

    global $wpdb;

    $sub_table = 'CREATE TABLE IF NOT EXISTS `wp_wf_subscribers` (
        `sub_id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) DEFAULT NULL,
        `email` varchar(165) DEFAULT NULL,
        `sujet` varchar(50) DEFAULT NULL,
        `texte` varchar (1000) DEFAULT NULL,
        PRIMARY KEY (`sub_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=latin1;';

    $wpdb->query( $sub_table );

}

// Insérer des donnnées dans la table

function wf_save_subscriber($name, $email, $sujet, $texte){

    global $wpdb;

    $data = array(
        'name' => $name,
        'email' => $email,
        'sujet' => $sujet,
        'texte' => $texte
    );

    $wpdb->insert('wp_wf_subscribers',$data);

}

// Prendre les données dans le but de les afficher

function wf_get_list_subscribers(){

    global $wpdb;

    $sql = "SELECT * from wp_wf_subscribers";

    $results = $wpdb->get_results($sql,ARRAY_A);

    return $results;

}


// Supprimer un ligne de données dans la bdd

function wf_delete_subscriber_by_id( $id ){

    global $wpdb;

    $wpdb->query("DELETE FROM wp_wf_subscribers WHERE sub_id=". $id);
}


// Affiche un tableau avec les données de la base de données dans Mes Formulaire 

function get_html_page(){

    $o = "";
    $message = "";
    if(isset($_POST['wf_subscriber'])){

        $id = $_POST['wf_subscriber'];

        wf_delete_subscriber_by_id($id); // take note of this function
        echo '<div id="message" class="updated notice is-dismissible"><p>Les données ont été supprimé</p></div>';

    }


    $data = wf_get_list_subscribers();

    $o .= "<div class='wrap'> 
        <h1>Main Page</h1>
        </br>
        <a class='page-title-action' href='".menu_page_url("plugin-form-add", true)."'>Ajouter des données</a>
        <p>Voici la liste des formulaires envoyés. Pour ajouter le formulaire sur une page mettez : [devuptutorials]</p>
        <table style='width:100%' class='display wp-list-table widefat fixed'>";

    $o .= "<thead><tr><td>#</td><td>Nom</td><td>Email</td><td>Sujet</td><td>Message</td></tr></thead>";

    $o .= '</tr>';

    foreach ($data as $row){
        $o .= "<tr>";
        $o .="<td>".$row["sub_id"]."</td>";
        $o .="<td>".$row["name"]."</td>";
        $o .="<td>".$row["email"]."</td>";
        $o .="<td>".$row["sujet"]."</td>";
        $o .="<td>".$row["texte"]."</td>";
        $o .="<td><form style='display: inline' action='".menu_page_url('wf-my-menu',false)."' method='post' id='delete".$row['sub_id']."'>
        <a href='javascript:void()' onclick='document.getElementById(\"delete".$row["sub_id"]."\").submit()' class='trash'>Delete</a>
        <input type='hidden' name='wf_subscriber' value='".$row["sub_id"]."'/>
        </form></td>";
        $o .= "</tr>";
    }


    echo $o;

}


// Affiche un formulaire dans Ajouter un client pour pouvoir ajouter un client manuellement

function get_add_form () {

    $o = "";

    $message = "";

    $nameErr = $emailErr = $sujetErr = "";

    if(isset($_POST['wf_subscription_form'])){

        if(empty($_POST['wf_name_form_value'])){

            $nameErr = "Ecrivez le nom";

            $message ="<p style='color:#EF4351; font-size: 18px;'>$nameErr</p>";


        } else {

            $name = isset($_POST['wf_name_form_value']) ? $_POST['wf_name_form_value'] : null;


            if(empty($_POST['wf_email_form_value'])){

                $emailErr = "Ecrivez l'e-mail";

                $texte ="<p style='color:#EF4351; font-size: 18px;'>$emailErr</p>";


            } else {


                $email = isset($_POST['wf_email_form_value']) ? $_POST['wf_email_form_value'] : null;


                if(empty($_POST['wf_sujet_form_value'])){

                    $sujetErr = "Choisissez un sujet";

                    $message ="<p style='color:#EF4351; font-size: 18px;'>$sujetErr</p>";
            
                } else {

                    $sujet = isset($_POST['wf_sujet_form_value']) ? $_POST['wf_sujet_form_value'] : null;

                    $texte = isset($_POST['wf_texte_form_value']) ? $_POST['wf_texte_form_value'] : null;

                    if($name && $email && $sujet){

                        $message = "<p style='color:green; font-size: 18px;'>Votre formulaire a bien été envoyé</p>";

                        wf_save_subscriber($name, $email, $sujet, $texte);

                    }

                }                     
            
            }
        }
    }

    $o .= "<div class='wrap'> 
        <h1>Ajouter un Formulaire</h1>
        </br>
        <a class='page-title-action' href='".menu_page_url("plugin-form", true)."'>Voir les formulaires</a>
        <p>On peut ajouter des entrées de formulaire ici</p>
        $message



        <form method='post' action='".menu_page_url("plugin-form-add",false)."'>

        <p style='color:#65657b;'>* à remplir obligatoirement</p>

        <table class='form-table'>
            
            
            <tr>
                <th><label for='name'>Nom *</label></th>
                <td><input id='name' name='wf_name_form_value' type='text'/></td>
            </tr>

            <tr>
                <th><label for='email'>E-mail *</label></th>
                <td><input id='email' name='wf_email_form_value' type='email'/></td>
            </tr>

            <tr>
                <th><p>Sujet *</p></th>
                <td>
                    <label for='radio1'>Newsletter</label>
                    <input id='radio1' name='wf_sujet_form_value' type='radio' value='Newsletter'checked/>
                
                    <label for='radio2'>Evenement</label>
                    <input id='radio2' name='wf_sujet_form_value' type='radio' value='Evenement'/>
                
                    <label for='radio3'>Autres</label>
                    <input id='radio3' name='wf_sujet_form_value' type='radio' value='Autres'/>
                </td>

            </tr>

            <tr>
                <th><label for='message'>Message</label></th>
                <td><textarea id='texte' placeholder=' ' name='wf_texte_form_value'></textarea></td>
            </tr>

        </table>

        <p class='submit'>
            <input type='submit' name='wf_subscription_form' id='submit' class='button button-primary' value='Save the form'>
        </p>

        </form>

        ";
        
    echo $o;

}


// Affiche un formulaire dans le site grâce à un shortcode 

function wf_hello_world(){

    wp_enqueue_style('load-my-styles', plugins_url('./includes/css/style.css', __FILE__), array(), '1.0');

    $o = "";

    $nameErr = $emailErr = $sujetErr = "";


    if(isset($_POST['wf_subscription_form'])){
        


        if(empty($_POST['wf_name_form_value'])){

            $nameErr = "Ecrivez votre nom";

            $o .="<p style='color:#EF4351; font-size: 18px;'>$nameErr</p>";


        } else {

            $name = isset($_POST['wf_name_form_value']) ? $_POST['wf_name_form_value'] : null;


            if(empty($_POST['wf_email_form_value'])){

                $emailErr = "Ecrivez votre e-mail";

                $o .="<p style='color:#EF4351; font-size: 18px;'>$emailErr</p>";


            } else {


                $email = isset($_POST['wf_email_form_value']) ? $_POST['wf_email_form_value'] : null;


                if(empty($_POST['wf_sujet_form_value'])){

                    $sujetErr = "Choisissez un sujet";

                    $o .="<p style='color:#EF4351; font-size: 18px;'>$sujetErr</p>";
            
                } else {

                    $sujet = isset($_POST['wf_sujet_form_value']) ? $_POST['wf_sujet_form_value'] : null;

                    $texte = isset($_POST['wf_texte_form_value']) ? $_POST['wf_texte_form_value'] : null;

                    if($name && $email && $sujet){

                        $o .= "<p style='color:green; font-size: 18px;'>Votre formulaire a bien été envoyé</p>";

                        wf_save_subscriber($name, $email, $sujet, $texte);

                    }

                }                     
            
            }
        }


    }

    $o.= "<form method='post'>
    
            <div class='form'>
            <p style='color:#65657b;'>* à remplir obligatoirement</p>

                <div class='input-container ic1'>
                    <input id='firstname' class='input' type='text' placeholder=' ' name='wf_name_form_value' />
                    <div class='cut'></div>
                    <label for='firstname' class='placeholder'>Nom *</label>
                </div>

                <div class='input-container ic2'>
                    <input id='email' class='input' type='email' placeholder=' ' name='wf_email_form_value' />
                    <div class='cut cut-short'></div>
                    <label for='email' class='placeholder'>E-mail *</>
                </div>

                <div class='input-container ic1'>
                    <p class='label-sujet'>Sujet *</p>
                    <div class='wizard-form-radio'>
                        <label class='label' for='radio1'>Newsletter</label>
                        <input class='input-radio' name='wf_sujet_form_value' id='radio1' type='radio' value='Newsletter' checked>
                    </div>

                    <div class='wizard-form-radio'>
                        <label class='label' for='radio2'>Evenement</label>
                        <input class='input-radio' name='wf_sujet_form_value' id='radio2' type='radio' value='Evenement'>
                    </div>

                    <div class='wizard-form-radio'>
                        <label class='label' for='radio3'>Autres</label>
                        <input class='input-radio' name='wf_sujet_form_value' id='radio3' type='radio' value='Autres'>
                    </div>


                </div>
                </br>
                <div class='input-container ic2'>
                    <textarea id='texte' class='input' placeholder=' ' name='wf_texte_form_value'></textarea>
                    <div class='cut cut-short'></div>
                    <label for='texte' class='placeholder'>Message</>
                </div>
                <button type='submit' class='submit'>Envoyez</button>
                <input type='hidden' name='wf_subscription_form' value='dasfasdf' />
            </div>
        </form>";

    return $o;

}