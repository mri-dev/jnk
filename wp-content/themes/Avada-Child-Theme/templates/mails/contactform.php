<h1>Új <?php echo $contact_type; ?> érkezett!</h1>
<?php if (isset($name) && !empty($name)): ?>
<div>Név: <strong><?php echo $name; ?></strong></div>
<?php endif; ?>
<?php if (isset($email) && !empty($email)): ?>
<div>E-mail: <strong><?php echo $email; ?></strong></div>
<?php endif; ?>
<?php if (isset($subject) && !empty($subject)): ?>
<div>Téma: <strong><?php echo $subject; ?></strong></div>
<?php endif; ?>
<br>
<div>Üzenet: <br>
<strong><?php echo $uzenet; ?></strong></div>
<br><br>
-------- <br>
Küldve a(z) <strong><?php echo get_option('blogname'); ?></strong> weboldal kapcsolatfelvételi és ajánlatkérő rendszerével.
