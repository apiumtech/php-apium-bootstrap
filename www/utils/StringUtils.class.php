<?php
class StringUtils
{
	public static function Contains($string, $search)
	{
		if (strrpos($string, $search)===false)
	    {
	    	return false;
	    }  
	    return true;
	}
	public static function EndsWith($string, $endString)
	{
		$StrLen = strlen($endString);
        // Look at the end of FullStr for the substring the size of EndStr
	    $FullStrEnd = substr($string, strlen($string) - $StrLen);
        // If it matches, it does end with EndStr
    	return $FullStrEnd == $endString;
	}
	public static function BeginsWith($string, $beginString) 
	{
		$StrLen = strlen($beginString);
		$FullStrBegin = substr($string, 0, $StrLen);
    	return $FullStrBegin == $beginString;
	}
	public static function CleanHtmlNewLines($string) {
		return preg_replace("[\r\n]","&#13;&#10;", $string);
	}
	public static function CleanIndent($string) {
		return preg_replace("/[\n\r\t]/","",$string);
	}
    public static function isUtf8($string)
    {
        $out = iconv('UTF-8', 'UTF-8', $string);
        return ( $out == $string);
    }
	public static function EnsureUtf8(&$aString) {
		if (!self::isUtf8($aString))
		{
			$aString=utf8_encode($aString);
			return $aString;
		}
		return $aString;
	}
	public static function cutLastChar($string) 
	{
		return substr($string,0,strlen($string)-1);
	}
	public static function cutFirstChar($string) 
	{
		return substr($string,1,strlen($string)-1);
	}
	public static function trimLineByLine($txt, $replacement=" ")
    {
		$splitted = split("\n",$txt);
		$trimmedTXT = "";
		for ($i = 0; $i < count($splitted); $i++) {
			$trimmedTXT.=$replacement.trim($splitted[$i]);
		}
        $replacementLength=strlen($replacement);
		return substr($trimmedTXT,$replacementLength,strlen($trimmedTXT)-$replacementLength);
	}
	
	public static function getRandomString($length=10) 
	{
		$alpha_numeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        return substr(str_shuffle($alpha_numeric), 0, $length);
	}
	public static function getRandomStringUC($length=10) 
	{
        return strtoupper(self::getRandomString($length));
	}
	public static function countWords($str)
	{
		$words = 0;
		$array = self::toWordsArray($str);
		for($i=0;$i < count($array);$i++)
		{
			if (eregi("[".self::allAlphanumericChars."]", $array[$i]))
			$words++;
		}
		return $words;
	}	
	public static function toWordsArray($input) 
	{
		$str = eregi_replace(" +", " ", $input);
		$str = trim($str);
		if ($str==String_Empty) return array();
		$array = explode(" ", $str);
		return $array;
	}
	public static function escapeSql($input) 
	{
		return addslashes($input);
	}
	
	public static function lowerString(&$string){
	    $string = mb_convert_case($string, MB_CASE_LOWER, "UTF-8");
	 }

    public static function stripNonSentenceChars($input)
    {
        $newString = ereg_replace("[".self::not.self::allAlphanumericChars.self::_and.self::punctuationSigns."]", " ", $input);
        $newString = ereg_replace("[".self::escapeSlash."]", " ", $newString);
        $newString = ereg_replace("[ ]+", " ", $newString);
        return $newString;
    }
    public static function Utf8ToHTML($text)
    {
	        $character=0;
	        $iCount=0;
	        $strDest = "";

        for($i=0;$i<strlen($text);$i++) {
            if ($iCount==0) {
                $character = ord(substr($text,$i,1)) & 0xFF;
                if (($character&0xF0) == 0xF0) {
                        $iCount=3;
                        $character&=0x7;
                }
                else if (($character&0xF0) == 0xE0) {
                        $iCount=2;
                        $character&=0xF;
                }
                else if (($character&0xF0) == 0xC0) {
                        $iCount=1;
                        $character&=0x1F;
                }
            }
            else {
                $character = ($character << 6) | (ord(substr($text,$i,1)) & 0x3F);
                $iCount--;
            }

            if ($iCount==0) {
                if ($character>255) {
                    $strDest.=sprintf("&#x%04x;", $character);
                }
                else if ($character>127) {
                    $strDest.=sprintf("&#x%02x;", $character);
                }
                else {
                    $strDest.=chr($character);
                }
            }
        }

        return $iCount==0 ? $strDest : null;
    }
    public static function splitByLength($input,$maxChars)
    {
        $newText = wordwrap($input,$maxChars,"\n");
        return explode("\n",$newText);
    }


    public static function preg_pos( $subject, $regex )
    {        
        if( preg_match( '/'.$regex.'/', $subject, $matches ) ) {            
            return strpos( $subject, $matches[0]);
        }

        return -1;
    }


    public static function substringWithoutBrokenWords($source, $length)
    {
        $actualLength = 0;
        $words = split(" ", $source);
        $acumulativeString= "";
        $index = 0;
        while ($actualLength < $length) {
            $acumulativeString .= $words[$index]." ";
            $index ++;
            $actualLength = strlen($acumulativeString);
        }
        return trim($acumulativeString);
    }

    public static function isCapitalized($input)
    {
        if (strtoupper($input)==$input) return false;
        $input=self::stripNonAlphanumericChars($input);
        $input=trim($input);
        if (!$input) return false;
        //if (!self::isAlphanumeric($input)) return false;
        return ucfirst($input)===$input;
    }

    public static function isAlphanumeric($input)
    {
        $regExpToMatch='/^[\p{L}0-9\s]*$/u';
        return self::matches($regExpToMatch, $input);
    }

    private static function matches($regExpToMatch, $input)
    {
        $matches=array();
        return preg_match($regExpToMatch, $input, $matches)>0;
    }

    /**
     * @static
     * @param $input string
     * @return bool
     * a reference is considered to be something that express a dat, a serial code, or something alike
     */
    public static function isAReference($input)
    {
        if (!$input) return false;
        $regExpToMatch='/^[0-9]+[^\p{L}\s]*$/u';
        return self::matches($regExpToMatch, $input);
    }

    public static function normalizeQuotes($input)
    {
        $input=preg_replace("%[ʹʹʻʼʽʽʾʿ˂˃ˊˋˎˏ]%u", '\'', $input);
        $input=preg_replace("%[˝˴˶ʺ“‟„‟”]%u", "\"", $input);
        return $input;
    }

    public static function stripNonAlphanumericChars($input)
    {
        return preg_replace("/[^\p{L}0-9\s]/u", " ", $input);
    }

    public static function toBool($input)
    {
        if (strtolower($input)=="false") return false;
        if (strtolower($input)=="true") return true;
        return (bool) $input;
    }

    const allAlphanumericChars="0-9A-Za-zÀ-ÖØ-öø-ÿ";
    const punctuationSigns="\(\)\.\;\?\¡";
    const escapeSlash="\\\\";
    const not="^";
    const _and="";
    const _or="";

    //special chars
    const standardSingleQuote="'";
    const standardDoubleQuote='"';

    public static function changeLastPart($string, $needle, $closureToApply)
    {
        $split=preg_split("/$needle/", $string);
        $last = array_pop($split);
        $last=$closureToApply($last);
        array_push($split, $last);
        return str_replace("\\\\", "\\", join($needle, $split));
    }
}
?>