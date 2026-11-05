@extends('frontend.include.app')
@section('title', $title . ' | ' . setting('title', 'gali satta kings'))
@section('meta_description', \Illuminate\Support\Str::limit(trim(preg_replace('/\\s+/', ' ', strip_tags($meta_description))), 160))
@section('structured_data')
@php
  $pageCanonical = rtrim((string) setting('canonical_url', 'https://galisattakings.com'), '/') . request()->getPathInfo();
  $pageDescription = $page->meta_description ?: \Illuminate\Support\Str::limit(trim(preg_replace('/\\s+/', ' ', strip_tags((string) $page->content))), 160);
  $pageSchema = ['@context'=>'https://schema.org','@type'=>'WebPage','name'=>$page->meta_title ?: $page->name,'url'=>$pageCanonical,'description'=>$pageDescription,'breadcrumb'=>['@type'=>'BreadcrumbList','itemListElement'=>[['@type'=>'ListItem','position'=>1,'name'=>'Home','item'=>rtrim((string) setting('canonical_url', 'https://galisattakings.com'), '/') . '/'],['@type'=>'ListItem','position'=>2,'name'=>$page->name,'item'=>$pageCanonical]]]];
@endphp
<script type="application/ld+json">@json($pageSchema, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT)</script>
@endsection
@section('content')
<header class="v6-page-hero"><div class="v6-wrap"><nav class="v6-breadcrumb"><a href="{{ route('index') }}">Home</a> / {{ $page->name }}</nav><span class="v6-eyebrow eyebrow-purple">PUBLISHED INFORMATION</span><h1>{{ $page->name }}</h1>@if(filled($meta_description))<p>{{ $meta_description }}</p>@endif</div></header><section class="v6-wrap v6-page-content"><aside class="v6-page-rail"><b>Explore more</b><a href="{{ route('index') }}#today-results">Latest results ↗</a><a href="{{ route('charts.index') }}">Result charts ↗</a><a href="{{ route('community.index') }}">Community ↗</a></aside><article class="v6-document"><span class="v6-eyebrow eyebrow-blue">INFORMATION CENTRE</span><div class="gsk-information-copy mt-3">{!! $page->content !!}</div></article></section>

