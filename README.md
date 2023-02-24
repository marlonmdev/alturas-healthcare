# Alturas Healthcare

<img src="https://github.com/marlonmdev/alturas-healthcare/blob/main/assets/images/homepage-screenshot.png" alt="Login Image">

<em> Note : This app uses Hashids PHP library to encrypt numeric ids like this -> 12 to this format -> EXQNYv8gQ0dKwr4lyW3k</em>

<em>I configure Hashids to work with Codeigniter 3 and the files can be found in the following folders</em>

<code>-> assets/vendors/hashids/Hashids.php</code>
<code>-> application/config/hashids.php</code>
<code>-> system/helpers/hashids_helper.php</code>

<em>you can set the hashids salt in the config.php file found in application/config/</em>
<code>$config['hashid_salt'] = 'Your custom salt here';</code>

<em>I created a custom library called Myhash.php in application/folders/ which uses the Hashids</em>
