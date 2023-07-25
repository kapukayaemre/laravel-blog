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
