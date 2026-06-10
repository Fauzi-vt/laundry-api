{{-- 
    Partial: _payment_logo.blade.php
    Usage: @include('partials._payment_logo', ['code' => $acc->provider_code, 'size' => 'md'])
    
    Props:
      $code  - provider_code string (bca, bri, mandiri, bsi, bni, gopay, ovo, dana, shopeepay, cash)
      $size  - 'sm' | 'md' | 'lg' (default: 'md')
--}}
@php
$knownCodes = ['bca', 'bri', 'mandiri', 'bsi', 'bni', 'gopay', 'ovo', 'dana', 'shopeepay'];
$code = strtolower($code ?? '');

$bgColors = [
    'bca'       => '#005BAA',
    'bri'       => '#003D7C',
    'mandiri'   => '#003D7C',
    'bsi'       => '#1FAD49',
    'bni'       => '#E65100',
    'gopay'     => '#00AED6',
    'ovo'       => '#4C3494',
    'dana'      => '#118EEA',
    'shopeepay' => '#EE4D2D',
    'cash'      => '#10B981',
];

$bg = $bgColors[$code] ?? '#94A3B8';
$hasFile = in_array($code, $knownCodes);

$sizeClasses = match($size ?? 'md') {
    'sm'  => 'h-8',
    'lg'  => 'h-14',
    default => 'h-10',
};
@endphp

@if($code === 'cash')
{{-- Cash special icon --}}
<div class="w-full {{ $sizeClasses }} rounded-lg flex items-center justify-center flex-shrink-0" 
     style="background-color: {{ $bg }};">
    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
              d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
    </svg>
</div>

@elseif($hasFile)
{{-- Render logo from SVG file --}}
<div class="w-full {{ $sizeClasses }} rounded-lg overflow-hidden flex items-center justify-center flex-shrink-0"
     style="background-color: {{ $bg }};"
     title="{{ ucfirst($code) }}">
    <img src="{{ asset('images/payment/' . $code . '.svg') }}" 
         alt="{{ ucfirst($code) }} logo"
         class="w-full h-full object-contain p-1"
         loading="lazy"
         onerror="this.style.display='none'; this.parentElement.innerHTML='<span style=\'color:white;font-weight:900;font-size:11px;letter-spacing:1px;\'>' + '{{ strtoupper($code) }}' + '</span>'">
</div>

@else
{{-- Fallback generic badge --}}
<div class="w-full {{ $sizeClasses }} rounded-lg flex items-center justify-center flex-shrink-0"
     style="background-color: {{ $bg }};"
     title="{{ ucfirst($code) }}">
    <span style="color:white;font-weight:900;font-size:11px;letter-spacing:1px;">{{ strtoupper(substr($code, 0, 4)) }}</span>
</div>
@endif
