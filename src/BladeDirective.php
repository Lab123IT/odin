<?php
namespace Lab123\Odin;

use Illuminate\Support\Facades\Blade;

class BladeDirective
{

    public function active()
    {
        $this->now();
        
        $this->date();
    }

    private function now()
    {
        Blade::directive('now', function ($format) {
            
            $format = ($format !== '()') ?: 'd/m/Y H:i';
            
            $datetime = new \DateTime();
            $datetime->setTimezone(new \DateTimeZone('America/Sao_Paulo'));
            $date = $datetime->format($format);
            
            return "<?php echo '{$date}'; ?>";
        });
    }

    private function date()
    {
        Blade::directive('date', function ($date, $format = 'd/m/Y H:i') {
            return "<?php echo date('{$format}', strtotime{$date}); ?>";
        });
    }
}