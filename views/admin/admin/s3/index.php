<?php
$c = f()->config;
?>
<header>
	<h2>S3 Config</h2>
	<button class="save" form="edit-form">Save</button>
</header>

<p>This form helps guide you through configuring S3 for file uploads.</p>

<h3>How to do this whole process</h3>
<ol>
	<li>Visit the <a href="https://aws.amazon.com">AWS Management Console</a></li>
	<li>Create an IAM Role for this website.</li>
	<li>Make sure the new IAM Role has S3 access.</li>
	<li>Copy over the Access Key ID and Secret Access Key from that IAM role into the corresponding fields below.</li>
	<li>Create a bucket for this website.</li>
	<li>Copy the bucket name and region into the Bucket Config fields below.</li>
</ol>

<form action="" method="post" id="edit-form">
	<h3>IAM Role Config</h3>
	<p>This can be root account credentials, but I recommend setting up an IAM Role for each site.</p>
	<div class="field">
		<label for="s3_key">Access Key ID</label>
		<input type="text" id="s3_key" name="s3_key" value="<?=$c->s3_key?>">
	</div>
	<div class="field">
		<label for="s3_secret">Secret Access Key</label>
		<input type="text" id="s3_secret" name="s3_secret" value="<?=$c->s3_secret?>">
	</div>

	<h3>Bucket Config</h3>
	<div class="field">
		<label for="s3_bucket">Bucket</label>
		<input type="text" id="s3_bucket" name="s3_bucket" value="<?=$c->s3_bucket?>">
	</div>
	<div class="field">
		<label for="s3_region">Region</label>
		<input type="text" id="s3_region" name="s3_region" value="<?=$c->s3_region?>" placeholder="e.g. us-east-2">
	</div>

	<h3>Misc Config</h3>
	<p>I honestly can't figure out how this field works. Leaving this blank will use the value "latest" which seems to work. I saw one example that was a date formatted like "<?=date('Y-m-d')?>". Feel free to try whatever.</p>
	<div class="field">
		<label for="s3_version">Version</label>
		<input type="text" id="s3_version" name="s3_version" value="<?=$c->s3_version?>">
	</div>

<?php if(!empty($c->s3_bucket)){?>
	<h3>Enabling Public Read Access</h3>
	<p>In order to serve the files to the public, the buckets need to have public read access enabled. Since it's Amazon, it needs to be really complicated.</p>
	<ol>
		<li>Go to your bucket and go to the Permissions tab.</li>
		<li>Under Bucket Policy, paste this to enable public read access:</li>
	</ol>
	<pre>{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::<?=$c->s3_bucket?>/*"
        }
    ]
}</pre>
<?php }?>
</form>
