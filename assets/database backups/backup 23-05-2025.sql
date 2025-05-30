PGDMP  5                    }           postgres    17.4    17.4 3    c           0    0    ENCODING    ENCODING        SET client_encoding = 'UTF8';
                           false            d           0    0 
   STDSTRINGS 
   STDSTRINGS     (   SET standard_conforming_strings = 'on';
                           false            e           0    0 
   SEARCHPATH 
   SEARCHPATH     8   SELECT pg_catalog.set_config('search_path', '', false);
                           false            f           1262    5    postgres    DATABASE     n   CREATE DATABASE postgres WITH TEMPLATE = template0 ENCODING = 'UTF8' LOCALE_PROVIDER = libc LOCALE = 'it-IT';
    DROP DATABASE postgres;
                     postgres    false            g           0    0    DATABASE postgres    COMMENT     N   COMMENT ON DATABASE postgres IS 'default administrative connection database';
                        postgres    false    4966            �            1259    16863 
   challenges    TABLE     �   CREATE TABLE public.challenges (
    name text NOT NULL,
    description text,
    date_start timestamp without time zone DEFAULT CURRENT_TIMESTAMP,
    date_end timestamp without time zone,
    winners text[],
    image text
);
    DROP TABLE public.challenges;
       public         heap r       postgres    false            �            1259    16869    comments    TABLE     �   CREATE TABLE public.comments (
    id integer NOT NULL,
    user_id character(1),
    post_id integer,
    content text NOT NULL,
    created_at timestamp without time zone DEFAULT now()
);
    DROP TABLE public.comments;
       public         heap r       postgres    false            �            1259    16875    comments_id_seq    SEQUENCE     �   CREATE SEQUENCE public.comments_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 &   DROP SEQUENCE public.comments_id_seq;
       public               postgres    false    218            h           0    0    comments_id_seq    SEQUENCE OWNED BY     C   ALTER SEQUENCE public.comments_id_seq OWNED BY public.comments.id;
          public               postgres    false    219            �            1259    16876    drafts    TABLE     ,  CREATE TABLE public.drafts (
    id integer NOT NULL,
    creator text,
    created_at timestamp without time zone DEFAULT now() NOT NULL,
    type character varying(32),
    description text,
    tags text[],
    file_location character varying(255),
    variation_of text,
    challenge_of text
);
    DROP TABLE public.drafts;
       public         heap r       postgres    false            �            1259    16882    drafts_id_seq    SEQUENCE     �   CREATE SEQUENCE public.drafts_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 $   DROP SEQUENCE public.drafts_id_seq;
       public               postgres    false    220            i           0    0    drafts_id_seq    SEQUENCE OWNED BY     ?   ALTER SEQUENCE public.drafts_id_seq OWNED BY public.drafts.id;
          public               postgres    false    221            �            1259    16883    snips    TABLE     �  CREATE TABLE public.snips (
    id integer NOT NULL,
    creator text,
    views integer DEFAULT 0,
    created_at timestamp without time zone DEFAULT now(),
    description text,
    element_type character varying(32),
    tags text[],
    file_location character varying(255) NOT NULL,
    likes integer DEFAULT 0,
    saved integer DEFAULT 0,
    variation_of text,
    challenge_of text
);
    DROP TABLE public.snips;
       public         heap r       postgres    false            �            1259    16892    snips_id_seq    SEQUENCE     �   CREATE SEQUENCE public.snips_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.snips_id_seq;
       public               postgres    false    222            j           0    0    snips_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.snips_id_seq OWNED BY public.snips.id;
          public               postgres    false    223            �            1259    16972    snips_with_likes    VIEW     �  CREATE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
 #   DROP VIEW public.snips_with_likes;
       public       v       postgres    false            �            1259    16949    user_snip_likes    TABLE     d   CREATE TABLE public.user_snip_likes (
    user_id integer NOT NULL,
    snip_id integer NOT NULL
);
 #   DROP TABLE public.user_snip_likes;
       public         heap r       postgres    false            �            1259    16893    users    TABLE     M  CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(50) NOT NULL,
    email character varying(100) NOT NULL,
    password character varying(255) NOT NULL,
    likedsnippets text[],
    savedsnippets text[],
    bio text DEFAULT 'This is my bio :)'::text,
    followers text[],
    following text[]
);
    DROP TABLE public.users;
       public         heap r       postgres    false            �            1259    16899    users_id_seq    SEQUENCE     �   CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;
 #   DROP SEQUENCE public.users_id_seq;
       public               postgres    false    224            k           0    0    users_id_seq    SEQUENCE OWNED BY     =   ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;
          public               postgres    false    225            �           2604    16900    comments id    DEFAULT     j   ALTER TABLE ONLY public.comments ALTER COLUMN id SET DEFAULT nextval('public.comments_id_seq'::regclass);
 :   ALTER TABLE public.comments ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    219    218            �           2604    16901 	   drafts id    DEFAULT     f   ALTER TABLE ONLY public.drafts ALTER COLUMN id SET DEFAULT nextval('public.drafts_id_seq'::regclass);
 8   ALTER TABLE public.drafts ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    221    220            �           2604    16902    snips id    DEFAULT     d   ALTER TABLE ONLY public.snips ALTER COLUMN id SET DEFAULT nextval('public.snips_id_seq'::regclass);
 7   ALTER TABLE public.snips ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    223    222            �           2604    16903    users id    DEFAULT     d   ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);
 7   ALTER TABLE public.users ALTER COLUMN id DROP DEFAULT;
       public               postgres    false    225    224            W          0    16863 
   challenges 
   TABLE DATA           ]   COPY public.challenges (name, description, date_start, date_end, winners, image) FROM stdin;
    public               postgres    false    217   	B       X          0    16869    comments 
   TABLE DATA           M   COPY public.comments (id, user_id, post_id, content, created_at) FROM stdin;
    public               postgres    false    218   &C       Z          0    16876    drafts 
   TABLE DATA           }   COPY public.drafts (id, creator, created_at, type, description, tags, file_location, variation_of, challenge_of) FROM stdin;
    public               postgres    false    220   CC       \          0    16883    snips 
   TABLE DATA           �   COPY public.snips (id, creator, views, created_at, description, element_type, tags, file_location, likes, saved, variation_of, challenge_of) FROM stdin;
    public               postgres    false    222   �C       `          0    16949    user_snip_likes 
   TABLE DATA           ;   COPY public.user_snip_likes (user_id, snip_id) FROM stdin;
    public               postgres    false    226   HG       ^          0    16893    users 
   TABLE DATA           w   COPY public.users (id, username, email, password, likedsnippets, savedsnippets, bio, followers, following) FROM stdin;
    public               postgres    false    224   eG       l           0    0    comments_id_seq    SEQUENCE SET     >   SELECT pg_catalog.setval('public.comments_id_seq', 1, false);
          public               postgres    false    219            m           0    0    drafts_id_seq    SEQUENCE SET     <   SELECT pg_catalog.setval('public.drafts_id_seq', 19, true);
          public               postgres    false    221            n           0    0    snips_id_seq    SEQUENCE SET     ;   SELECT pg_catalog.setval('public.snips_id_seq', 44, true);
          public               postgres    false    223            o           0    0    users_id_seq    SEQUENCE SET     :   SELECT pg_catalog.setval('public.users_id_seq', 4, true);
          public               postgres    false    225            �           2606    16905    challenges challenges_pkey 
   CONSTRAINT     Z   ALTER TABLE ONLY public.challenges
    ADD CONSTRAINT challenges_pkey PRIMARY KEY (name);
 D   ALTER TABLE ONLY public.challenges DROP CONSTRAINT challenges_pkey;
       public                 postgres    false    217            �           2606    16907    comments comments_pkey 
   CONSTRAINT     T   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_pkey PRIMARY KEY (id);
 @   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_pkey;
       public                 postgres    false    218            �           2606    16909    drafts drafts_pkey 
   CONSTRAINT     P   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_pkey PRIMARY KEY (id);
 <   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_pkey;
       public                 postgres    false    220            �           2606    16911    snips snips_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_pkey;
       public                 postgres    false    222            �           2606    16953 $   user_snip_likes user_snip_likes_pkey 
   CONSTRAINT     p   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_pkey PRIMARY KEY (user_id, snip_id);
 N   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_pkey;
       public                 postgres    false    226    226            �           2606    16913    users users_email_key 
   CONSTRAINT     Q   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_key UNIQUE (email);
 ?   ALTER TABLE ONLY public.users DROP CONSTRAINT users_email_key;
       public                 postgres    false    224            �           2606    16915    users users_pkey 
   CONSTRAINT     N   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);
 :   ALTER TABLE ONLY public.users DROP CONSTRAINT users_pkey;
       public                 postgres    false    224            �           2606    16917    users users_username_key 
   CONSTRAINT     W   ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);
 B   ALTER TABLE ONLY public.users DROP CONSTRAINT users_username_key;
       public                 postgres    false    224            V           2618    16975    snips_with_likes _RETURN    RULE     �  CREATE OR REPLACE VIEW public.snips_with_likes AS
 SELECT s.id,
    s.creator,
    s.views,
    s.created_at,
    s.description,
    s.element_type,
    s.tags,
    s.file_location,
    s.likes,
    s.saved,
    s.variation_of,
    s.challenge_of,
    count(usl.snip_id) AS challenge_likes
   FROM (public.snips s
     LEFT JOIN public.user_snip_likes usl ON ((s.id = usl.snip_id)))
  GROUP BY s.id;
 �  CREATE OR REPLACE VIEW public.snips_with_likes AS
SELECT
    NULL::integer AS id,
    NULL::text AS creator,
    NULL::integer AS views,
    NULL::timestamp without time zone AS created_at,
    NULL::text AS description,
    NULL::character varying(32) AS element_type,
    NULL::text[] AS tags,
    NULL::character varying(255) AS file_location,
    NULL::integer AS likes,
    NULL::integer AS saved,
    NULL::text AS variation_of,
    NULL::text AS challenge_of,
    NULL::bigint AS challenge_likes;
       public               postgres    false    4788    222    222    222    222    222    222    222    222    222    222    222    226    222    227            �           2606    16918    comments comments_post_id_fkey    FK CONSTRAINT     }   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_post_id_fkey FOREIGN KEY (post_id) REFERENCES public.snips(id);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_post_id_fkey;
       public               postgres    false    222    4788    218            �           2606    16923    comments comments_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.comments
    ADD CONSTRAINT comments_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(username);
 H   ALTER TABLE ONLY public.comments DROP CONSTRAINT comments_user_id_fkey;
       public               postgres    false    218    4794    224            �           2606    16928    drafts drafts_challange_of_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_challange_of_fkey FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 I   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_challange_of_fkey;
       public               postgres    false    217    220    4782            �           2606    16933    drafts drafts_creator_fkey    FK CONSTRAINT        ALTER TABLE ONLY public.drafts
    ADD CONSTRAINT drafts_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username);
 D   ALTER TABLE ONLY public.drafts DROP CONSTRAINT drafts_creator_fkey;
       public               postgres    false    224    220    4794            �           2606    16938    snips snips_creator_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT snips_creator_fkey FOREIGN KEY (creator) REFERENCES public.users(username) ON UPDATE CASCADE;
 B   ALTER TABLE ONLY public.snips DROP CONSTRAINT snips_creator_fkey;
       public               postgres    false    4794    224    222            �           2606    16959 ,   user_snip_likes user_snip_likes_snip_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_snip_id_fkey FOREIGN KEY (snip_id) REFERENCES public.snips(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_snip_id_fkey;
       public               postgres    false    222    226    4788            �           2606    16954 ,   user_snip_likes user_snip_likes_user_id_fkey    FK CONSTRAINT     �   ALTER TABLE ONLY public.user_snip_likes
    ADD CONSTRAINT user_snip_likes_user_id_fkey FOREIGN KEY (user_id) REFERENCES public.users(id) ON DELETE CASCADE;
 V   ALTER TABLE ONLY public.user_snip_likes DROP CONSTRAINT user_snip_likes_user_id_fkey;
       public               postgres    false    4792    226    224            �           2606    16943    snips vincolo_challenge    FK CONSTRAINT     �   ALTER TABLE ONLY public.snips
    ADD CONSTRAINT vincolo_challenge FOREIGN KEY (challenge_of) REFERENCES public.challenges(name);
 A   ALTER TABLE ONLY public.snips DROP CONSTRAINT vincolo_challenge;
       public               postgres    false    222    217    4782            W     x��ӱn�0���� LBیE�"�#Re�ɸ�GZ���Z�� ��ý�=�c.��Kx��D��4drB�
�@8�t�[��%�C�r��c)ӊ�$�J�E�vC�U�:�yl�x;�{?<x�{p}>�`��sQr��1H&Id��gV�D%Б����Z�Q�bsn��*�O{�'X��R�y��Kէ+/g�n��e�,�����u�_���˚Y躗6n��8֥�����fB��9��c���:�-��)n�����m��G�      X      x������ � �      Z   �   x���;�0E�Ud0I��0�4A�@�$�w�Vft���C#�b��<x�b�SBS��I��NFk�ñig����^ܰ�����Fg[5ɡ;%!���.B���h��ɪ�q�KLc&S^*[��:��%y�h��f$�k~�D��B9wl��ι�<��>B���Z�      \   9  x��T]o�6}���h$�-�l+,�1�
�DKL$R�(�j���+Yr�tCaY�/?�=�K7BVv1q��[��237��ď�(�� B�����2�Ĺ�V5V��V��2�io��h^�!��$�UV�J�q��ݡ����=�(|�|�g��%��$�9� q]ǃ��̘
WF�E����<�;�f�$�|F�\��P��^+
�1��7TY���	#��|^1���y�'K��@l[S7�L�J�^d#J��]�X�+Jn|s��O��N�F��ml)[���J��ѓ^� ?��])-�yz��#�7���&��%�Y�v���F��>����<�~(4�1�	��wb{a�:k�����_���I���� [�@VB�`ۚ�18S^��l���&�u��TS�~;5�ie;��ҹ9vr	����\Ƀ����ܸ.j�NT��<xM;���7r���>G��0<���<bp]-���4�\<����
$a�0��/�`����	g�:4�=�VY���C1��E��nz�^>MPd���Nh9.�S?rfk;ҫ<�xB�s��g|�+�E�q>�:yq¼R�n{���hx�9�wp��~��ֆ��JM������oS��M�s�S����
#:�S˶�.7@�Y�o�	�/v��!m�A��ق{R��]�2���Y/;��=�F
�Y�`g��<��$+|�j���	^��aN�%,t� ����O���boZ]��i���ͻw�_o߿��3�&�j�Y-�$��ZL��I��cQ�]]V�(�C��Ø�?�����duqLO3��	p!|q6�Ϳ5�׽      `      x������ � �      ^   �  x���Qo�0��ï���I����Ҧ%�49����6!&!����ZT��=L:������΁ڞ��R�`5���X&7)G� Xr�۞e���t��x����@��j\Db}0=T�\VANW�N�oݗ�P{U�RL�?/�Gʪ�I���Lv�|Ҟ��:}m�*R'����8�4��b.]�sK�f��	dr-�j��x�e��bcF�Țc����h(�L\��9&OӼ���E��þ��1��}���#f�Jd���ܜ�R�ٻ�?�-�7�۰�G�QC��u������䘸�r�l�� �6��pŏ�� ��G^=�����T�=�z��6��0=EJI���ji��}ǎ���RY�Ss~���.E��<�N���E��     