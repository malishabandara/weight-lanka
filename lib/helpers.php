<?php
declare(strict_types=1);

function h(?string $value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

function wl_flash_set(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function wl_flash_get(): ?array
{
    if (!isset($_SESSION['flash'])) {
        return null;
    }
    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);
    return $flash;
}

function wl_redirect(string $to): never
{
    header("Location: {$to}");
    exit;
}

function wl_date_add_one_year(string $dateYmd): string
{
    $dt = DateTimeImmutable::createFromFormat('Y-m-d', $dateYmd);
    if (!$dt) {
        return $dateYmd;
    }
    return $dt->modify('+1 year')->format('Y-m-d');
}

function wl_status_badge(string $status): string
{
    $status = strtolower(trim($status));
    return match ($status) {
        'active' => "<span class='badge text-bg-success'>Active</span>",
        'expired' => "<span class='badge text-bg-danger'>Expired</span>",
        default => "<span class='badge text-bg-secondary'>" . h($status) . "</span>",
    };
}

