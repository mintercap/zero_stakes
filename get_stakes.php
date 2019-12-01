<?php

require_once("config.php");

$merged = [];

foreach (Config::$Snapshots as $ss)
{
	$reward_height = Helper::GetLastRewardHeight($ss["height"]);
	$f = 'data/' . $reward_height . ".json";
	if ( !file_exists($f) )
	{
		echo "file {$f} does not exists";
		exit(1);
	}
	$stakes = GetStakes($f, $ss["pubkey"]);
	
	// save each node wallets
	$data_str = StakesToString($stakes);
	file_put_contents($ss["filename"], $data_str);
	
	MergeStakes($merged, $stakes);
}
// exclude wallets with less than minimal defined stake
$filtered = FilterByMinStake($merged);
arsort($filtered);

// save result wallets
$data_str = StakesToString($filtered);
file_put_contents(Config::$ResultFile, $data_str);



function GetStakes($filename, $pubkey)
{
	$stakes = [];
	$data = file_get_contents($filename);
	$json = json_decode($data);
	
	foreach ($json->candidates as $r)
	{
		// filter by public key
		if ($r->pub_key != $pubkey)
		{
			continue ;
		}
		/*
		 * {"owner":"Mx28ebe0ca6bc8759ba6f4d3b991cea1eeaa3205b0",
		 * "coin":"ZERO",
		 * "value":"520000000000000000000",
		 * "bip_value":"19589251186354662603"}
		 */
		foreach ($r->stakes as $s)
		{
			// filter by coin
			if ($s->coin != Config::$Coin)
			{
				continue ;
			}
			$coin_val = Helper::pip2bip($s->value);
			if ( !array_key_exists($s->owner, $stakes) )
			{
				$stakes[$s->owner] = 0;
			}
			$stakes[$s->owner] = bcadd($stakes[$s->owner], $coin_val, 18);
		}
		break;
	}
	return ($stakes);
}


function MergeStakes(&$stakes1, &$stakes2)
{
	foreach ($stakes2 as $k => $v)
	{
		if ( !array_key_exists($k, $stakes1) )
		{
			$stakes1[$k] = $v;
		}
		else
		{
			$stakes1[$k] = bcadd($stakes1[$k], $v, 18);
		}
	}
}

function FilterByMinStake($stakes)
{
	$new_stakes = [];
	
	foreach ($stakes as $k => $v)
	{
		// filter by stake
		if ( bccomp($v, Config::$CoinMinStake) < 0 )
		{
			continue ;
		}
		$new_stakes[$k] = $v;
	}
	return ($new_stakes);
}

function StakesToString($stakes)
{
	$str = "";
	foreach ($stakes as $k => $v)
	{
		$str .= $k . (!Config::$ExportOnlyAddress ? ("\t" . $v) : '') . "\n";
	}
	return ($str);
}




class Helper
{
	public static function GetLastRewardHeight($height)
	{
		$h = $height;
		while( !self::IsRewardHeight($h) )
		{
			$h--;
		}
		return ($h);
	}

	public static function IsRewardHeight($height)
	{
		return ($height % Config::REWARD_BLOCK_INTERVAL == 0);
	}
	
    public static function bip2pip($val)
    {
        return bcmul($val, 1000000000000000000);
    }
	
    public static function pip2bip($val)
    {
        return bcdiv($val, 1000000000000000000, 18);
    }
}