<?php

/**
 * A method for finding n-th prime numbers. Usage example follows at the end.
 * @author Paul Scott <paul.scotto@gmail.com>
 */

/**
 * Calculate the n-th prime number(s).
 * 
 * This function operates on the realisation that a number is prime if a factor
 * cannot be found in the primes less than it (since all numbers can be expressed as
 * a product of primes). We can further optimise this process by only considering
 * primes less than or equal to the square root as potential factors.
 *
 * @param int|array $nth
 *        The index/indices of the primes you want.
 * @param int|false $t
 *        The number of seconds to allow for calculation, or false (denoting no
 *        time limit (though bare max_execution_time in mind)).
 * @param int $tCheck
 *        How frequently (in terms of numbers tested for being prime) a breached
 *        time limit is checked for.
 *
 * @return int|array|null
 *        Returns an integer if $nth is an integer and an array if $nth an
 *        array. The array is associative of the form $n => get_prime($n), where
 *        $n is an integer within the original $nth array.
 *            If the time limit is reached, null is returned where $nth is an
 *        integer and an array with any computed primes is returned where $nth
 *        is an array.
 */
function get_prime($nth, $t = false, $tCheck = 1000)
{
    // Transform the arguments into a common form and discard bad n-ths
    $singular = !is_array($nth);
    $nth = array_filter((array) $nth,
        function($n) {
            return is_int($n) && $n > 0;
        });
    if (!$nth) return $singular ? null : array();

    // The n-th prime were aiming for
    $n = max($nth);

    // The first prime is the only even one
    $primes = array(1 => 2);
    if ($n == 1) {
        return $singular ? $primes[1] : $primes;
    }

    // Loop counters
    $c = 1;
    $p = 3;
    $begin = microtime(true);

    while (true)
    {
        // Check if $p is prime
        $prime = true;
        $sqrt = sqrt($p);
        for ($i = 1; $i < $c && $primes[$i] <= $sqrt; $i++) {
            if ($p % $primes[$i] == 0) {
                $prime = false;
                break;
            }
        }
        // Record $p if prime
        if ($prime) {
            $primes[++$c] = $p;
            if ($c == $n) {
                break;
            }
        }
        // Check if time limit expired (every $tCheck passes)
        if ($t && ($p % $tCheck <= 1) && (microtime(true) - $begin) > $t) {
            break;
        }
        // Next $p to check
        $p += 2;
    }
    
    if ($singular) {
        return isset($primes[$n]) ? $primes[$n] : null;
    } else {
        return array_intersect_key($primes, array_fill_keys($nth, null));
    }
}

function stopwatch($fn, $dump = true)
{
    $t = microtime(true);
    $result = $fn();
    $t = microtime(true) - $t;
    
    echo '<em>Call took ', $t, ' seconds.</em>';
    if ($dump) var_dump($result);
    return $result;
}
//echo "Prime of 129th number = ".get_prime(129);
?>

