@extends('layouts/contentNavbarLayout')

@section('title', 'Log - View')

@section('page-style')
<style>
  .json {
    font-size: 16px;

    &> {
      .json__item {
        display: block;
      }
    }
  }

  .json__item {
    display: block;
    margin-top: 10px;
    padding-left: 20px;
    user-select: block;
  }

  .json__item--collapsible {
    cursor: pointer;
    overflow: hidden;
    position: relative;

    &::before {
      content: '+';
      position: absolute;
      left: 5px;
    }

    &::after {
      background-color: lightgrey;
      content: '';
      height: 100%;
      left: 9px;
      position: absolute;
      top: 26px;
      width: 1px;
    }

    &:hover {
      &>.json__key,
      &>.json__value {
        text-decoration: underline;
      }
    }
  }

  .json__toggle {
    display: none;

    &:checked~.json__item {
      display: none;
    }
  }

  .json__key {
    color: grey;
    display: inline;

    &::after {
      content: ': ';
    }
  }

  .json__value {
    display: inline;
  }

  .json__value--string {
    color: green;
  }

  .json__value--number {
    color: green;
  }

  .json__value--boolean {
    color: red;
  }
</style>
@endsection
@section('content')
<h4 class="py-3 mb-4"><span class="text-muted fw-light">Logs /</span> View</h4>

<div class="card mb-4">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h5 class="mb-0">Audit Log Details</h5>
  </div>
  <div class="card-body">
    <p><strong>User:</strong> {{ $log->user?->name }}</p>
    <p><strong>Action Type:</strong> {{ $log->description }}</p>
    <p><strong>Action At:</strong> {{ $log->created_at }}</p>
    <p><strong>Subject Type:</strong> {{ $log->subject_type }}</p>
    <p><strong>Host:</strong> {{ $log->host }}</p>
    <p><strong>Properties:</strong></p>
    <pre><code class="target"></code></pre>
  </div>
</div>
@endsection

@section('page-script')

<script>
  function jsonViewer(json, collapsible = false) {
    var TEMPLATES = {
      item: '<div class="json__item"><div class="json__key">%KEY%</div><div class="json__value json__value--%TYPE%">%VALUE%</div></div>',
      itemCollapsible: '<label class="json__item json__item--collapsible"><input type="checkbox" class="json__toggle"/><div class="json__key">%KEY%</div><div class="json__value json__value--type-%TYPE%">%VALUE%</div>%CHILDREN%</label>',
      itemCollapsibleOpen: '<label class="json__item json__item--collapsible"><input type="checkbox" class="json__toggle"/><div class="json__key">%KEY%</div><div class="json__value json__value--type-%TYPE%">%VALUE%</div>%CHILDREN%</label>'
    };

    function createItem(key, value, type) {
      var element = TEMPLATES.item.replace('%KEY%', key);

      if (type == 'string') {
        element = element.replace('%VALUE%', '"' + value + '"');
      } else {
        element = element.replace('%VALUE%', value);
      }

      element = element.replace('%TYPE%', type);

      return element;
    }

    function createCollapsibleItem(key, value, type, children) {
      var tpl = 'itemCollapsible';

      if (collapsible) {
        tpl = 'itemCollapsibleOpen';
      }

      var element = TEMPLATES[tpl].replace('%KEY%', key);

      element = element.replace('%VALUE%', type);
      element = element.replace('%TYPE%', type);
      element = element.replace('%CHILDREN%', children);

      return element;
    }

    function handleChildren(key, value, type) {
      var html = '';

      for (var item in value) {
        var _key = item,
          _val = value[item];

        html += handleItem(_key, _val);
      }

      return createCollapsibleItem(key, value, type, html);
    }

    function handleItem(key, value) {
      var type = typeof value;

      if (typeof value === 'object') {
        return handleChildren(key, value, type);
      }

      return createItem(key, value, type);
    }

    function parseObject(obj) {
      _result = '<div class="json">';

      for (var item in obj) {
        var key = item,
          value = obj[item];

        _result += handleItem(key, value);
      }

      _result += '</div>';

      return _result;
    }

    return parseObject(json);
  };
  function findDifferences(oldData, newData) {
    let differences = {};

    for (let key in newData) {
      if (oldData[key] !== newData[key]) {
        differences[key] = { old: oldData[key], new: newData[key] };
      }
    }

    return differences;
  }


  var json = {!! $log->properties !!};
  json = findDifferences(json.old, json.new);

  var el = document.querySelector('.target');
  el.innerHTML = jsonViewer(json, true);
</script>
@endsection