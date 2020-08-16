@extends('layouts.app')

@section('content2')
    @if(Auth::check())
    <template v-if="menu==0">
        <welcome></welcome>
    </template>

    <template v-if="menu==1">
        <category></category>
    </template>

    <template v-if="menu==2">
        <product></product>
    </template>

    <template v-if="menu==4">
        <provider></provider>
    </template>
    @endif

@endsection
