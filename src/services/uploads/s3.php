<?php
namespace funky\services\uploads;

class s3 extends base{
	protected $client;
	protected $bucket;

	public function __construct(){
		$key = f()->config->s3_key;
		$secret = f()->config->s3_secret;
		$region = f()->config->s3_region;
		$version = isset(f()->config->s3_version) ? f()->config->s3_version : 'latest';

		$credentials = new \Aws\Credentials\Credentials($key, $secret);
		$this->client = new \Aws\S3\S3Client([
			'version'=>$version,
			'region'=>$region,
			'credentials'=>$credentials
		]);
		$this->bucket = f()->config->s3_bucket;
	}

	// uploads a file with the given filename and content
	public function put($filename, $content){
		$this->client->putObject([
			'Bucket'=>$this->bucket,
			'Key'=>$filename,
			'Body'=>$content,
		]);
	}

	// returns the file content for the given filename
	public function get($filename){
		$result = $this->client->getObject([
			'Bucket'=>$this->bucket,
			'Key'=>$filename,
		]);
		return $result['Body'];
	}

	public function url($filename){
		$bucket = f()->config->s3_bucket;
		$region = f()->config->s3_region;
		return "https://$bucket.s3.$region.amazonaws.com/$filename";
	}

	// deletes the file if it exists
	public function delete($filename){
		$this->client->deleteObject([
			'Bucket'=>$this->bucket,
			'Key'=>$filename,
		]);
	}

	// returns true if the file exists
	// returns false if it doesn't.
	public function exists($filename){
		try{
			// this will throw an exception if the file is not found
			$this->client->headObject([
				'Bucket'=>$this->bucket,
				'Key'=>$filename,
			]);

			// so in this context, an exception was not thrown, so it exists
			return true;
		}catch(\Exception $e){
			return false;
		}
	}

	// returns an array of all filenames in the bucket.
	// note: this could take awhile to load the huge array
	public function all(){
		$objects = $this->client->getIterator('ListObjects', [
			'Bucket'=>$this->bucket,
		]);

		$all = [];
		foreach($objects as $obj){
			$all[] = $obj['Key'];
		}
		return $all;
	}

	// searches over filenames and returns an array of all filenames that exist that match
	public function search($query){
		die("TODO s3.search");
	}
}