<?php

class Config
{
    const BLOCK_INTERVAL = 5;
    const BLOCKS_PER_MINUTE = 60 / self::BLOCK_INTERVAL;
	const REWARD_BLOCK_INTERVAL = self::BLOCKS_PER_MINUTE;
	
	public static $Coin = "ZERO";
	public static $CoinMinStake = 100;
	public static $ExportOnlyAddress = false;
	
	public static $Snapshots = [
		[
			"pubkey" => "Mp1ada5ac409b965623bf6a4320260190038ae27230abfb5ebc9158280cdffffff",
			"height" => 2356158, // 1 Oct 2019 00:00 UTC
			"filename" => "result/masteryoda.txt",
			"merge" => true,
		],
		[
			"pubkey" => "Mpeee9614b63a7ed6370ccd1fa227222fa30d6106770145c55bd4b482b88888888",
			"height" => 2884972, // 1 Nov 2019 00:00 UTC
			"filename" => "result/cat.txt",
			"merge" => true,
		],
		[
			"pubkey" => "Mp120c15e48aed0ac866a1a918bd367cfa31909a6b09f328a18bd18f32edef2be8",
			"height" => 3393923, // 1 Dec 2019 00:00 UTC
			"filename" => "result/minterpro2.txt",
			"merge" => true,
		],
		[
			"pubkey" => "Mp5e3e1da62c7eabd9d8d168a36a92727fc1970a54ec61eadd285d4199c41191d7",
			"height" => 3912348, // 1 Jan 2020 00:00 UTC
			"filename" => "result/mintercap.txt",
			"merge" => false
		]
	];

	public static $ResultFile = "result/all.txt";
}

