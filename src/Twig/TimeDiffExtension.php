<?php
    namespace App\Twig;

    use Twig\Extension\AbstractExtension;
    use Twig\TwigFilter;

    class TimeDiffExtension extends AbstractExtension
    {
    public function getFilters()
    {
    return [
    new TwigFilter('time_diff', [$this, 'getTimeDiff']),
    ];
    }

    public function getTimeDiff(\DateTimeInterface $dateTime)
    {
    $now = new \DateTime();
    $interval = $now->diff($dateTime);

    $parts = [];
    if ($interval->y > 0) {
    $parts[] = $interval->y . ' année' . ($interval->y > 1 ? 's' : '');
    }
    if ($interval->m > 0) {
    $parts[] = $interval->m . ' mois' . ($interval->m > 1 ? 's' : '');
    }
    if ($interval->d > 0) {
    $parts[] = $interval->d . ' jour' . ($interval->d > 1 ? 's' : '');
    }
    if ($interval->h > 0) {
    $parts[] = $interval->h . ' heure' . ($interval->h > 1 ? 's' : '');
    }
    if ($interval->i > 0) {
    $parts[] = $interval->i . ' minute' . ($interval->i > 1 ? 's' : '');
    }

    if (empty($parts)) {
    return 'maintenant';
    }

    return implode(', ', $parts) . ' ago';
    }
    }