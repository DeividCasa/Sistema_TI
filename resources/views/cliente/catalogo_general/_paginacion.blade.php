@if($totalPaginas > 1)
  <button type="button" class="pag-btn" {{ $page <= 1 ? 'disabled' : '' }} onclick="irAPaginaGeneral({{ $page - 1 }})">&lt;</button>

  @php
    $inicio = max(1, $page - 2);
    $fin    = min($totalPaginas, $inicio + 4);
    $inicio = max(1, $fin - 4);
  @endphp

  @if($inicio > 1)
    <button type="button" class="pag-btn" onclick="irAPaginaGeneral(1)">1</button>
    @if($inicio > 2)
      <span class="pag-dots">…</span>
    @endif
  @endif

  @for ($i = $inicio; $i <= $fin; $i++)
    <button type="button" class="pag-btn {{ $i === $page ? 'pag-btn-active' : '' }}" onclick="irAPaginaGeneral({{ $i }})">{{ $i }}</button>
  @endfor

  @if($fin < $totalPaginas)
    @if($fin < $totalPaginas - 1)
      <span class="pag-dots">…</span>
    @endif
    <button type="button" class="pag-btn" onclick="irAPaginaGeneral({{ $totalPaginas }})">{{ $totalPaginas }}</button>
  @endif

  <button type="button" class="pag-btn" {{ $page >= $totalPaginas ? 'disabled' : '' }} onclick="irAPaginaGeneral({{ $page + 1 }})">&gt;</button>
@endif

<style>
  .pag-btn {
    min-width: 38px; height: 38px; padding: 0 8px;
    border: 1px solid #ebebeb; background: #fff; color: #333;
    border-radius: 6px; font-weight: 600; font-size: 0.9rem;
    cursor: pointer; transition: all 0.2s;
  }
  .pag-btn:hover:not(:disabled) { border-color: var(--blue); color: var(--blue); }
  .pag-btn:disabled { opacity: 0.4; cursor: not-allowed; }
  .pag-btn-active { background: var(--blue); border-color: var(--blue); color: #fff; }
  .pag-dots { padding: 0 4px; color: #999; }
</style>
