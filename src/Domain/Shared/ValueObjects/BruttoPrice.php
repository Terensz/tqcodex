<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObjects;

/**
 * Price based on brutto
 */
final class BruttoPrice
{
    public const HUF = 'HUF';

    public const EUR = 'EUR';

    public const USD = 'USD';

    public readonly float $netto_price;

    public readonly float $brutto_price;

    public readonly string $currency;

    public readonly string $vat_rate;

    public readonly string $formatted_netto;

    public readonly string $formatted_brutto;

    /**
     * @var array<string, string>
     */
    protected array $currencies = [
        'HUF' => 'Ft',
        'USD' => '$',
        'EUR' => '€',
    ];

    public function __construct(float $netto_price, string $vat_rate = '27', string $currency = 'HUF')
    {
        if ($netto_price < 0) {
            throw new \InvalidArgumentException("A nettó ár csak pozitív szám lehet: {$netto_price}.");
        }

        if (! in_array($currency, $this->getAvailableCurrencies())) {
            throw new \InvalidArgumentException("A pénznem HUF, EUR vagy USD lehet csak: {$currency}.");
        }

        $this->netto_price = $netto_price;
        $this->currency = $currency;
        $this->vat_rate = $vat_rate;
        $brutto = $netto_price;
        if (is_numeric($vat_rate)) {
            $brutto = $netto_price * (1 + floatval($vat_rate) / 100);
        }
        $this->brutto_price = $brutto;
        $this->formatted_netto = number_format(round($this->netto_price), 0, ',', '.')." {$this->currencies[$currency]}";
        $this->formatted_brutto = number_format(round($this->brutto_price), 0, ',', '.')." {$this->currencies[$currency]}";
    }

    /**
     * @return array<int, string>
     */
    private function getAvailableCurrencies(): array
    {
        return [self::HUF, self::EUR, self::USD];
    }
}
