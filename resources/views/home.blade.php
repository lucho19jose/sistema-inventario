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

    <template v-if="menu==3">
        <inputproduct></inputproduct>
    </template>

    <template v-if="menu==4">
        <provider></provider>
    </template>

    <template v-if="menu==6">
        <staff></staff>
    </template>

    <template v-if="menu==7">
        <branch></branch>
    </template>
    @endif

@endsection
