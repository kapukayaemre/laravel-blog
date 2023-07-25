@isset($isResponsive)
    <div class="table-responsive">
@endisset
        <table class="table {{ $class ?? '' }}">
            @isset($columns)
                <thead>
                <tr>
                    {!! $columns !!}
                </tr>
                </thead>
            @endisset

            @isset($rows)
                <tbody>
                {!! $rows !!}
                </tbody>
            @endisset
        </table>
@isset($isResponsive)
    </div>
@endisset
