<?php
namespace App\Http\Controllers\Merchant; use App\Library\Helper; use App\Library\Response; use App\System; use Illuminate\Database\Eloquent\Relations\Relation; use Illuminate\Http\Request; use App\Http\Controllers\Controller; use Illuminate\Support\Facades\DB; use voku\helper\AntiXSS; class Product extends Controller { function get(Request $sp62e4cd) { $sp4210ad = $this->authQuery($sp62e4cd, \App\Product::class)->with(array('category' => function (Relation $sp4210ad) { $sp4210ad->select(array('id', 'name', 'password_open')); })); $spe0aaed = $sp62e4cd->input('search', false); $sp75c4d8 = $sp62e4cd->input('val', false); if ($spe0aaed && $sp75c4d8) { if ($spe0aaed == 'simple') { if ($sp62e4cd->input('from') === 'card') { $sp4210ad->where('delivery', \App\Product::DELIVERY_AUTO); } return Response::success($sp4210ad->where('category_id', $sp75c4d8)->get(array('id', 'name'))); } elseif ($spe0aaed == 'id') { $sp4210ad->where('id', $sp75c4d8); } elseif ($spe0aaed == 'category_id') { $sp4210ad->where('category_id', $sp75c4d8); } else { $sp4210ad->where($spe0aaed, 'like', '%' . $sp75c4d8 . '%'); } } $sp93712d = (int) $sp62e4cd->input('category_id'); if ($sp93712d > 0) { $sp4210ad->where('category_id', $sp93712d); } $sp3022f1 = $sp62e4cd->input('enabled'); if (strlen($sp3022f1)) { $sp4210ad->whereIn('enabled', explode(',', $sp3022f1)); } $sp295466 = (int) $sp62e4cd->input('current_page', 1); $spe5b040 = (int) $sp62e4cd->input('per_page', 20); $sp6492f8 = $sp4210ad->orderBy('sort')->paginate($spe5b040, array('*'), 'page', $sp295466); foreach ($sp6492f8->items() as $spfd49bd) { $spfd49bd->setAppends(array('count', 'url')); } return Response::success($sp6492f8); } function sort(Request $sp62e4cd) { $this->validate($sp62e4cd, array('id' => 'required|integer', 'sort' => 'required|integer')); $spfd49bd = $this->authQuery($sp62e4cd, \App\Product::class)->findOrFail($sp62e4cd->post('id')); $spfd49bd->sort = $sp62e4cd->post('sort'); $spfd49bd->saveOrFail(); return Response::success(); } function set_count(Request $sp62e4cd) { $this->validate($sp62e4cd, array('id' => 'required|integer', 'count' => 'required|integer')); $spfd49bd = $this->authQuery($sp62e4cd, \App\Product::class)->findOrFail($sp62e4cd->post('id')); $spfd49bd->count_all = $spfd49bd->count_sold + $sp62e4cd->post('count'); $spfd49bd->saveOrFail(); return Response::success(); } function category_change(Request $sp62e4cd) { $this->validate($sp62e4cd, array('id' => 'required|integer', 'category_id' => 'required')); $spfd49bd = $this->authQuery($sp62e4cd, \App\Product::class)->findOrFail($sp62e4cd->post('id')); $sp93712d = $sp62e4cd->input('category_id'); if (is_string($sp93712d) && @$sp93712d[0] === '+') { $sp1b7790 = \App\Category::create(array('user_id' => $spfd49bd->user_id, 'name' => substr($sp93712d, 1), 'enabled' => true)); } else { $sp1b7790 = $this->authQuery($sp62e4cd, \App\Category::class)->findOrFail($sp93712d); } $spfd49bd->category_id = $sp1b7790->id; $spfd49bd->save(); return Response::success($sp1b7790); } function edit(Request $sp62e4cd) { $this->validate($sp62e4cd, array('id' => 'sometimes|integer', 'category_id' => 'required', 'description' => 'required|string', 'instructions' => 'required|string', 'fields' => 'required|string', 'sort' => 'required|integer|min:0|max:10000000', 'inventory' => 'required|integer|between:0,2', 'buy_min' => 'required|integer|min:0|max:10000', 'buy_max' => 'required|integer|min:0|max:10000', 'cost' => 'required|numeric|min:0|max:10000000', 'price' => 'required|numeric|min:0.01|max:10000000', 'price_whole' => 'required|string', 'enabled' => 'required|integer|between:0,1')); $sp93712d = $sp62e4cd->post('category_id'); $sp1e841c = is_string($sp93712d) && @$sp93712d[0] === '+'; if ($sp1e841c) { $sp1b7790 = \App\Category::create(array('user_id' => $this->getUserIdOrFail($sp62e4cd), 'name' => substr($sp93712d, 1), 'enabled' => true)); } else { $sp1b7790 = $this->authQuery($sp62e4cd, \App\Category::class)->where('id', @intval($sp93712d))->first(); if (!$sp1b7790) { return Response::fail('商品分类不存在'); } } $spb54a76 = $sp62e4cd->post('name'); $sp38971b = $sp62e4cd->post('description'); $spf0716a = $sp62e4cd->post('instructions'); $spea491b = (int) $sp62e4cd->post('buy_min', 0); $spea85c5 = (int) $sp62e4cd->post('buy_max', 0); $sp56f888 = (int) round($sp62e4cd->post('cost') * 100); $sp1d9b51 = (int) round($sp62e4cd->post('price') * 100); $spe9a4d6 = $sp62e4cd->post('price_whole'); $spcb8775 = @json_decode($spe9a4d6, true); foreach ($spcb8775 as $spdaefa9) { if ($spdaefa9[1] < 1 || $spdaefa9[1] > 1000000000) { return Response::fail('商品批发价需要在 0.01-10000000 之间'); } } if (System::_getInt('filter_words_open') === 1) { $sp773684 = explode('|', System::_get('filter_words')); if (($spa59707 = Helper::filterWords($spb54a76, $sp773684)) !== false) { return Response::fail('提交失败! 商品名称包含敏感词: ' . $spa59707); } if (($spa59707 = Helper::filterWords($sp38971b, $sp773684)) !== false) { return Response::fail('提交失败! 商品描述包含敏感词: ' . $spa59707); } if (($spa59707 = Helper::filterWords($spf0716a, $sp773684)) !== false) { return Response::fail('提交失败! 商品使用说明包含敏感词: ' . $spa59707); } } if ((int) $sp62e4cd->post('id')) { $spfd49bd = $this->authQuery($sp62e4cd, \App\Product::class)->findOrFail($sp62e4cd->post('id')); } else { $spfd49bd = new \App\Product(); $spfd49bd->count_sold = 0; $spfd49bd->user_id = $this->getUserIdOrFail($sp62e4cd); } $spfd49bd->category_id = $sp1b7790->id; $spfd49bd->name = $spb54a76; $spd8b04e = new AntiXSS(); $spfd49bd->description = $spd8b04e->xss_clean($sp38971b); $spfd49bd->instructions = $spd8b04e->xss_clean($spf0716a); $spfd49bd->fields = $sp62e4cd->post('fields'); $spfd49bd->delivery = (int) $sp62e4cd->post('delivery'); $spfd49bd->sort = $sp62e4cd->post('sort'); $spfd49bd->buy_min = $spea491b; $spfd49bd->buy_max = $spea85c5; $spfd49bd->count_warn = $sp62e4cd->post('count_warn'); $spfd49bd->support_coupon = $sp62e4cd->post('support_coupon') === 'true'; $spfd49bd->password = $sp62e4cd->post('password'); $spfd49bd->password_open = $sp62e4cd->post('password_open') === 'true'; $spfd49bd->cost = $sp56f888; $spfd49bd->price = $sp1d9b51; $spfd49bd->price_whole = $spe9a4d6; $spfd49bd->enabled = (int) $sp62e4cd->post('enabled'); $spfd49bd->inventory = (int) $sp62e4cd->post('inventory'); $spfd49bd->saveOrFail(); $sp3e8d87 = array(); if ($sp1e841c) { $sp3e8d87['category'] = $sp1b7790; } return Response::success($sp3e8d87); } function enable(Request $sp62e4cd) { $this->validate($sp62e4cd, array('ids' => 'required|string', 'enabled' => 'required|integer')); $spb0cc9a = $sp62e4cd->post('ids'); $sp3022f1 = (int) $sp62e4cd->post('enabled'); $this->authQuery($sp62e4cd, \App\Product::class)->whereIn('id', explode(',', $spb0cc9a))->update(array('enabled' => $sp3022f1)); return Response::success(); } function delete(Request $sp62e4cd) { $this->validate($sp62e4cd, array('ids' => 'required|string')); $spb0cc9a = $sp62e4cd->post('ids'); $spb0cc9a = explode(',', $spb0cc9a); $sp0e0496 = $this->authQuery($sp62e4cd, \App\Product::class)->whereIn('id', $spb0cc9a); $sp07aa06 = $this->authQuery($sp62e4cd, \App\Card::class)->whereIn('product_id', $spb0cc9a); return DB::transaction(function () use($sp0e0496, $sp07aa06) { $sp0e0496->delete(); $sp07aa06->forceDelete(); return Response::success(); }); } function count_sync(Request $sp62e4cd) { \App\Product::refreshCount($this->getUser($sp62e4cd)); return Response::success(); } }