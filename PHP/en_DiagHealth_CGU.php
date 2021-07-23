<?php 
	session_start();

	$ma_fonction = function(int $value, string $message, string $file, int $line) {
				switch ($value) {
					case E_USER_ERROR:
						echo 'Erreur de type : ' .$value .' à la ligne ' .$line .'<br/>';
						break;
					case E_USER_WARNING:
						echo $message .' dans le fichier ' .$file .'<br/>';
						break;
					case E_USER_NOTICE:
						echo 'Erreur E_USER_NOTICE <br/>';
						break;
					case E_NOTICE:
						echo '';
						break;
					
					default:
						echo 'Valeur erreur par defaut : ' .$value .'<br/>';
						echo 'Le problème est : ' .$message .'<br/>';
						break;
				}
	};

	// définir ma fonction comme gestionnaire d'erreur
	set_error_handler($ma_fonction);

	$_SESSION['page'] = "CGU";

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8"/>
        <link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_base.css">
		<link rel="stylesheet" type="text/css" href="../Public/CSS/DiagHealth_CGU.css">
        <title>CGU</title>
    </head>
    <?php include 'en_DiagHealth_header.php'; ?>
    <body>
        <h1>Terms and conditions of use</h1>

        <h5>Effective as of 06/04/2021</h5>

        <p class="cgu">The purpose of these general terms of use (known as "GTU") is to provide a legal framework for
            of the terms and conditions for the provision of the site and the services by _______________ and to define the
            conditions of access and use of the services by the "User".
            The present GTU are accessible on the site under the heading "GTU".
            Any registration or use of the site implies the acceptance without any reserve or restriction of the
            of these GTU by the user. When registering on the site via the Registration Form, each user expressly accepts the
            user expressly accepts the present GTU by checking the box preceding the following text: "I acknowledge that I have read and understood the GTU.
            I acknowledge that I have read and understood the TOS and I accept them".
            In case of non-acceptance of the GTU stipulated in this contract, the User must renounce
            access to the services offered by the site.
            diaghealth.com reserves the right to unilaterally modify at any time the content of the
            of these GTUs at any time.

Translated with www.DeepL.com/Translator (free version)</p>

        <h2>ARTICLE 1 : The legal mentions</h2>

        <p class="cgu">The editing and the direction of the publication of the diaghealth.com website is ensured by APP G7B, domiciled at 28
            Rue Notre Dame des Champs.
            Telephone number is _______________
            E-mail address diaghealth@gmail.com.
            The host of the diaghealth.com site is the company _______________, whose head office is located at
            _______________, with the phone number : _______________.</p>

        <h2>ARTICLE 2 : Access to the site</h2>

        <p class="cgu">The diaghealth.com website allows the User free access to the following services:
            The website offers the following services:
            access to psychometric test results.
            The site is accessible free of charge anywhere to any User with Internet access. All the
            costs incurred by the User to access the service (hardware, software, Internet connection, etc.) are
            Internet connection, etc.) are at the User's expense.
            The non-member User does not have access to the reserved services. To do so, they must register by
            filling out the form. By agreeing to register for the reserved services, the member User
            agrees to provide true and accurate information about his civil status and contact information,
            in particular his email address.
            To access the services, the User must then identify himself using his login and password, which will be
            which will be communicated to him after his registration.
            Any User member who is regularly registered may also request to be removed from the list by
            by going to the dedicated page on his personal space. This will be effective within a reasonable time.
            Any event due to a case of force majeure resulting in a malfunction of the site or server and subject to any
            or server and subject to any interruption or modification in case of maintenance, does not engage the responsibility
            responsibility of diaghealth.com. In these cases, the User agrees not to hold the publisher responsible for any interruption or
            the editor of any interruption or suspension of service, even without notice.
            The User has the possibility of contacting the site by electronic messaging to the email address of
            the editor communicated in ARTICLE 1.</p>

        <h2>ARTICLE 3 : Data collection</h2>

        <p class="cgu">The site is exempt from declaration to the Commission Nationale Informatique et Libertés (CNIL) insofar as it does not
            insofar as it does not collect any data concerning the Users.</p>

        <h2>ARTICLE 4 : Intellectual property</h2>

        <p class="cgu">The brands, logos, signs and all the contents of the site (texts, images, sound...) are protected by the Code of Intellectual Property and particularly by
            of a protection by the Code of the intellectual property and more particularly by the copyright.
            The User must request prior authorization from the site for any reproduction, publication or copy of the
            different contents. The User undertakes to use the contents of the site in a strictly private context,
            Any use for commercial and advertising purposes is strictly forbidden.
            Any total or partial representation of this site by any process whatsoever, without the express permission of the
            the operator of the website would constitute an infringement punishable by Article L 335-2
            and following of the Code of the intellectual property.
            It is recalled in accordance with Article L122-5 of the Code of Intellectual Property that the User who
            reproduces, copies or publishes protected content must cite the author and source.</p>

        <h2>ARTICLE 5 : Responsibility</h2>

        <p class="cgu">The sources of the information published on the diaghealth.com site are deemed reliable but the site does not
            site does not guarantee that it is free of defects, errors or omissions.
            The information communicated is presented for information purposes only and has no contractual value.
            Despite regular updates, the diaghealth.com website cannot be held responsible for any
            administrative and legal provisions occurring after publication. Similarly, the
            site cannot be held responsible for the use and interpretation of the information contained in this site.
            this site.
            The User is responsible for keeping his/her password secret. Any disclosure of the password, in whatever form, is
            any form, is prohibited. The User assumes all risks related to the use of his login and password.
            and password. The site declines all responsibility.
            The site diaghealth.com cannot be held responsible for any virus that could infect
            computer or any computer equipment of the Internet user, following use, access, or downloading from this
            downloading from this site.
            The responsibility of the site can not be engaged in case of force majeure or unforeseeable and insurmountable
            and insurmountable of a third party.</p>

        <h2>ARTICLE 6 : Hypertext links</h2>

        <p class="cgu">Hypertext links may be present on the site. The User is informed that by clicking on these
            links, he/she will leave the diaghealth.com site. The latter has no control over the web pages to which these links lead and
            The latter has no control over the web pages to which these links lead and cannot, under any circumstances, be held responsible for their content.</p>

        <h2>ARTICLE 7 : Cookies</h2>

        <p class="cgu">The User is informed that during his visits to the site, a cookie may be automatically installed on his
            on his navigation software.
            Cookies are small files temporarily stored on the hard disk of the User's computer by your browser and which are
            by your browser and which are necessary for the use of the diaghealth.com website. The
            contain no personal information and cannot be used to identify anyone.
            to identify anyone. A cookie contains a unique identifier that is randomly generated and therefore anonymous. Some
            expire at the end of the User's visit, others remain.
            The information contained in the cookies is used to improve the diaghealth.com site.
            By browsing the site, the User accepts them.
            The User can deactivate these cookies by using the settings in his/her browser software.
            navigation software.</p>

        <h2>ARTICLE 8 : Publication by the User</h2>

        <p class="cgu">The site allows members to publish the following content:
            forum.
            In his publications, the member agrees to respect the rules of Netiquette (rules of good conduct of the
            conduct of the Internet) and the rules of law in force.
            The site can exercise a moderation on the publications and reserves the right to refuse their setting in
            online, without having to justify this to the member.
            The member remains the owner of all his intellectual property rights. But by publishing a
            publication on the site, he/she gives the publishing company the non-exclusive and free right to represent,
            reproduce, adapt, modify, broadcast and distribute its publication, directly or through an authorized third party,
            in the whole world, on any support (digital or physical), for the duration of the
            intellectual property. In particular, the Member grants the right to use its publication on the Internet and on
            cell phone networks.
            The publishing company undertakes to include the Member's name in the vicinity of each use of his or her
            publication.
            Any content put online by the User is of his sole responsibility. The User undertakes not to
            not to put on line contents which can damage the interests of third people. Any
            Any legal action taken by an injured third party against the site will be borne by the User.
            The User's content may be removed or modified by the site at any time and for any reason
            by the site, without notice.</p>

        <h2>ARTICLE 9: Applicable law and jurisdiction</h2>

        <p class="cgu">French law applies to this contract. In the event of failure to reach an amicable settlement of a
            dispute between the parties, the French courts will have exclusive jurisdiction.
            For any question relating to the application of the present GCU, you can join the editor to the
            coordinates listed in ARTICLE 1.</p>  
    </body>
    <?php include 'en_DiagHealth_footer.php'; ?>
</html>