<?php

declare(strict_types=1);

namespace Domain\Shared\ValueObjects;

/**
 * Price based on brutto
 */
final class Price
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

    public function __construct(float $brutto_price, string $vat_rate = '27', string $currency = 'HUF')
    {
        if ($brutto_price < 0) {
            throw new \InvalidArgumentException("A bruttó ár csak pozitív szám lehet: {$brutto_price}.");
        }

        if (! in_array($currency, $this->getAvailableCurrencies())) {
            throw new \InvalidArgumentException("A pénznem HUF, EUR vagy USD lehet csak: {$currency}.");
        }

        $this->brutto_price = $brutto_price;
        $this->currency = $currency;
        $this->vat_rate = $vat_rate;
        $netto = $brutto_price;
        if (is_numeric($vat_rate)) {
            $vat_amount = $brutto_price / (100 + floatval($vat_rate)) * floatval($vat_rate);
            $netto = $this->brutto_price - $vat_amount;
        }
        $this->netto_price = $netto;
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
