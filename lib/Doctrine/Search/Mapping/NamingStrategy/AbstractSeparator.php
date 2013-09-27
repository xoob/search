<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.doctrine-project.org>.
 */

namespace Doctrine\Search\Mapping\NamingStrategy;

abstract class AbstractSeparator extends Literal
{
    /**
     * Word separation character
     * @var string
     */
    protected $separator = '?';

    /**
     * {@inheritDoc}
     */
    public function classToTableName($value)
    {
        return $this->camelCaseToSeparator(parent::classToTableName($value), $this->separator);
    }

    /**
     * {@inheritDoc}
     */
    public function propertyToColumnName($value)
    {
        $value = parent::propertyToColumnName($value);
        
        foreach (array($this->separator, '-', '_') as $separator) {
            if (strpos($value, $separator) !== false) {
                $value = $this->separatorToCamelCase($value, $separator);
            }
        }

        return $this->camelCaseToSeparator($value, $this->separator);
    }
    
    /**
     * @param string $string
     * @return string
     */
    protected function camelCaseToSeparator($value, $separator, $case = CASE_LOWER)
    {
        $pattern = array('#(?<=(?:\p{Lu}))(\p{Lu}\p{Ll})#', '#(?<=(?:\p{Ll}|\p{Nd}))(\p{Lu})#');
        $replacement = array($separator . '\1', $separator . '\1');

        $pattern = array('#(?<=(?:[A-Z]))([A-Z]+)([A-Z][a-z])#', '#(?<=(?:[a-z0-9]))([A-Z])#');
        $replacement = array('\1' . $separator . '\2', $separator . '\1');

        $filtered = preg_replace($pattern, $replacement, $value);

        switch ($case) {
            case CASE_LOWER:
                $filtered = strtolower($filtered);
                break;

            case CASE_UPPER:
                $filtered = strtoupper($filtered);
                break;
        }

        return $filtered;
    }

    /**
     * @param string $string
     * @return string
     */
    protected function separatorToCamelCase($value, $separator)
    {
        $patterns = array(
            '#(' . preg_quote($separator, '#') . ')([A-Za-z]{1})#',
            '#(^[A-Za-z]{1})#',
        );
        $replacements = array(
            function ($matches) {
                return strtoupper($matches[2]);
            },
            function ($matches) {
                return strtoupper($matches[1]);
            },
        );

        $filtered = $value;

        foreach ($patterns as $index => $pattern) {
            $filtered = preg_replace_callback($pattern, $replacements[$index], $filtered);
        }

        return $filtered;
    }
}



